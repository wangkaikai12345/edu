<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/8/23
 * Time: 14:11
 */

namespace App\Traits;

trait ResponseWithToken
{
    /**
     * Get the token array structure.
     *
     * @param  string $token
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}