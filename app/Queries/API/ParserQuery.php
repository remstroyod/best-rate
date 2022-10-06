<?php

namespace App\Queries\Api;

use Illuminate\Database\Eloquent\Builder;

class ParserQuery extends Builder
{

    /**
     * @param $send_currency
     * @param $recive_currency
     * @return $this
     */
    public function whereFilter($send_currency = null, $recive_currency = null): self
    {

        if( $send_currency ) $this->where('start_exchange', $send_currency);

        if( $recive_currency ) $this->where('end_exchanhe', $recive_currency);

        return $this;
    }

}
