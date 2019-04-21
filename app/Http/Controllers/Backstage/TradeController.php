<?php

namespace App\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;
use App\Http\Transformers\TradeTransformer;
use App\Models\Trade;

class TradeController extends Controller
{

    public function index(Trade $trade)
    {
        $data = $trade->paginate(self::perPage());

        return $this->response->paginator($data, new TradeTransformer());
    }

    public function show(Trade $trade)
    {
        return $this->response->item($trade, new TradeTransformer());
    }
}
