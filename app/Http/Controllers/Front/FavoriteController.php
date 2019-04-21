<?php

namespace App\Http\Controllers\Front;

use App\Enums\FavoriteType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Front\FavoriteRequest;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{

//    public function index(Request $request)
//    {
//        // 默认展示自己收藏
//        !$request->user_id && $request->offsetSet('user_id', auth()->id());
//        // 默认展示课程收藏
//        !$request->model_type && $request->offsetSet('model_type', FavoriteType::COURSE);
//
//        $favorites = Favorite::filtered()->sorted()->paginate(self::perPage());
//
//        return $this->response->paginator($favorites, new FavoriteTransformer());
//    }

    public function store(FavoriteRequest $request)
    {
        $where = ['model_id' => (int)$request->model_id, 'model_type' => $request->model_type, 'user_id' => auth('web')->id()];

        $favorite = Favorite::where($where)->exists();

        if (!$favorite) {

            $favorite = new Favorite($request->all());
            $favorite->user_id = auth('web')->id();
            $favorite->save();

            return ajax('200', '点赞成功', $favorite);

        } else {

            Favorite::where($where)->first()->delete();

            return ajax('200', '取消点赞成功', ['取消']);
        }

    }
}
