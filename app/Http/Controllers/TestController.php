<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    /**
     * 测试路由
     */
    public function index()
    {
        abort(404);
//        $oldMysql = DB::connection('ydma_cn');
//
//        $datas = $oldMysql->table('videos')->get();
//
//        $datas->each(function ($data) {
//            dd($data);
//        });
    }
}
