<?php

namespace App\Models;

use App\Queries\Api\ParserQuery;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parse extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'parses';

    /**
     * @var string[]
     */
    protected $fillable = [
        'ident',
        'start_exchange',
        'end_exchanhe',
        'start_rate',
        'end_rate',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @param $query
     * @return ParserQuery
     */
    public function newEloquentBuilder($query)
    {

        return new ParserQuery($query);

    }
}
