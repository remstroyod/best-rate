<?php

namespace App\Http\Handlers;

use App\Models\Parse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class ParserHandler extends BaseHandler
{

    private $url = 'http://api.bestchange.ru/info.zip';
    //private $url = 'http://best-rate.loc/info.zip'; //Test Url File
    private $file = 'info.zip';
    private $readFile = 'bm_rates.dat';

    /**
     * @param Request $request
     * @return void|null
     */
    public function process(Request $request)
    {

        try {

            $response = Http::get($this->url);

            if( $response->clientError() ) return __('Файл не доступен');

            $file = Storage::disk('public')->put($this->file, $response->body());

            if( $file )
            {

                $unzipFile = $this->unZip($this->file);
                if( !$unzipFile )
                {
                    return __('Ошибка распаковки файла');
                }

            }else{

                return __('Файл не найден');

            }

            $parse = $this->parse();

            return $parse;

        } catch (\Throwable $e) {

            $this->setErrors($e->getMessage());
            return null;

        }

    }

    /**
     * @param $file
     * @return bool
     */
    private function unZip($file): ?bool
    {

        try {

            $zip = new ZipArchive();
            $storageFile = storage_path("app/public/$file");

            $res = $zip->open($storageFile);

            if ($res === true)
            {

                $zip->extractTo(storage_path("app/public/unzip/"));
                $zip->close();

                Storage::disk('public')->delete($this->file);

                return true;

            } else {

                return false;

            }

        } catch (\Throwable $e) {

            $this->setErrors($e->getMessage());
            return null;

        }

    }

    /**
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|string|null
     */
    private function parse(): array|string|\Illuminate\Contracts\Translation\Translator|\Illuminate\Contracts\Foundation\Application|null
    {

        try {

            if (Storage::disk('public')->missing('unzip/' . $this->readFile)) return __( 'Файл для парсинга не найден' );

            $contents = Storage::disk('public')->get('unzip/' . $this->readFile);

            $collection = Str::of($contents)->split('/[\s\n]+/');

            $result = new Collection();

            collect($collection)->map(function ($value) use ($result)
            {

                $arr = Str::of($value)->split('/[\s;]+/');

                if( $arr )
                {
                    $newKey = $arr[0] . $arr[1];

                    if( $result->has($newKey) )
                    {

                        if( $arr[4] > $result->get($newKey)['end_rate'] )
                        {
                            $result->replace([
                                $newKey => [
                                    'end_rate' => $arr[4]
                                ]
                            ]);
                        };

                    }else{

                        $result->put($newKey, [
                            'start_exchange' => $arr[0],
                            'end_exchanhe' => $arr[1],
                            'start_rate' => $arr[3],
                            'end_rate' => $arr[4]
                        ]);

                    }
                }

            });

            if( $result->count() )
            {

                $result->each(function ($item, $key) {

                    if( count($item) )
                    {
                        Parse::updateOrCreate([
                            'ident' => $key,
                        ],
                            [
                                'ident' => $key,
                                'start_exchange' => $item['start_exchange'],
                                'end_exchanhe' => $item['end_exchanhe'],
                                'start_rate' => $item['start_rate'],
                                'end_rate' => $item['end_rate'],
                            ],
                        );
                    }

                });
            }

            Storage::disk('public')->deleteDirectory('unzip');

            return __( 'Данные получены' );


        } catch (\Throwable $e) {

            $this->setErrors($e->getMessage());
            return null;

        }

    }

}
