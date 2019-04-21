<?php

namespace App\Http\Requests\Front;

use App\Http\Requests\BaseRequest;

class SaveChunkRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 分片总数量
            'chunk_total' => 'required|numeric',
            // 分片索引
            'chunk_index' => 'required|numeric',
            // 文件名
            'resource_temp_basename' => 'required|string',
            // 文件后缀
            'resource_ext' => 'required|string',
            // 获取分片的文件数据
            'resource_chunk' => 'required|file',
            // 保存的路径
            'group_subdir' => 'required|string',
            // 文件hash值
            'resource_hash' => 'required|string',
            // 分组
            'group' => 'required|string',
        ];
    }
}
