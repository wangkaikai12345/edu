<?php

namespace App\Http\Controllers\Web;

use App\Enums\FavoriteType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\FavoriteRequest;
use App\Http\Transformers\FavoriteTransformer;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * @SWG\Get(
     *  path="/favorites",
     *  tags={"web/favorite"},
     *  summary="列表",
     *  description="允许查看他人收藏，默认展示自己的收藏",
     *  @SWG\Parameter(ref="#/parameters/FavoriteQuery-user_id"),
     *  @SWG\Parameter(ref="#/parameters/FavoriteQuery-model_type"),
     *  @SWG\Parameter(ref="#/parameters/Favorite-sort"),
     *  @SWG\Parameter(ref="#/parameters/Favorite-include"),
     *  @SWG\Parameter(ref="#/parameters/page"),
     *  @SWG\Parameter(ref="#/parameters/per_page"),
     *  @SWG\Response(response=200,ref="#/responses/FavoritePagination"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function index(Request $request)
    {
        // 默认展示自己收藏
        !$request->user_id && $request->offsetSet('user_id', auth()->id());
        // 默认展示课程收藏
        !$request->model_type && $request->offsetSet('model_type', FavoriteType::COURSE);

        $favorites = Favorite::filtered()->sorted()->paginate(self::perPage());

        return $this->response->paginator($favorites, new FavoriteTransformer());
    }

    /**
     * @SWG\Post(
     *  path="/favorites",
     *  tags={"web/favorite"},
     *  summary="添加/取消",
     *  description="未收藏则会添加收藏；否则取消收藏；",
     *  @SWG\Parameter(ref="#/parameters/FavoriteForm-model_type"),
     *  @SWG\Parameter(ref="#/parameters/FavoriteForm-model_id"),
     *  @SWG\Response(response=204,description="ok"),
     *  @SWG\Response(response=422,ref="#/responses/ResourceException"),
     *  security={
     *      {"Bearer": {}}
     *  },
     * )
     */
    public function store(FavoriteRequest $request)
    {
        $favorite = Favorite::where('model_id', $request->model_id)
            ->where('model_type', $request->model_type)
            ->where('user_id', auth()->id())
            ->first();
        if (!$favorite) {
            $favorite = new Favorite($request->all());
            $favorite->user_id = auth()->id();
            $favorite->save();
        } else {
            $favorite->delete();
        }

        return $this->response->noContent();
    }
}
