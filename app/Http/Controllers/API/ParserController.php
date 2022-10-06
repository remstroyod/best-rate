<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\ParserResource;
use App\Models\Parse;
use Illuminate\Http\Request;

class ParserController extends Controller
{

    /**
     * @return void
     */
    public function index(Request $request, $send_currency = null, $recive_currency = null)
    {

        if( $request->has('send_currency') ) $send_currency = $request->input('send_currency');
        if( $request->has('recive_currency') ) $recive_currency = $request->input('recive_currency');

        $courses = Parse::whereFilter($send_currency, $recive_currency)
            ->paginate(20)
            ->appends($request->input());

        return ParserResource::collection($courses);

    }

}
