<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Transformers\TradeTransformer;
use App\Models\Trade;

class TradeController extends Controller
{
    /**
     * @SWG\Tag(name="admin/trade",description="交易记录")
     */

    /**
     * @SWG\Get(
     *  path="/admin/trades",
     *  tags={"admin/trade"},
     *  summary="列表",
     *  description="",
     *  @SWG\Response(response=200,ref="#/responses/TradePagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index(Trade $trade)
    {
        $data = $trade->paginate(self::perPage());

        return $this->response->paginator($data, new TradeTransformer());
    }

    /**
     * @SWG\Get(
     *  path="/admin/trades/{trade_id}",
     *  tags={"admin/order"},
     *  summary="详情",
     *  description="",
     *  @SWG\Parameter(name="trade_id",type="integer",in="path",required=true),
     *  @SWG\Response(response=200,description="",@SWG\Schema(ref="#/definitions/Trade")),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function show(Trade $trade)
    {
        return $this->response->item($trade, new TradeTransformer());
    }
}
