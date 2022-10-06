<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class ParserResource extends JsonResource
{

    /**
     * @var bool
     */
    public $preserveKeys = true;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'start_exchange' => $this->start_exchange,
            'end_exchanhe' => $this->end_exchanhe,
            'start_rate' => $this->start_rate,
            'end_rate' => $this->end_rate,
        ];

    }

}
