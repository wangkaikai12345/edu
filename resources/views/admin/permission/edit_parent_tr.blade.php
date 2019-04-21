<td>组-{{$permission->title}}</td>
<td>
    <button class="btn btn-sm btn-primary"
            data-target="#mySimpleModal" data-toggle="modal"
            onclick="showSimpleModal('{{ route('backstage.permissions.edit', ['permission' => $permission->hashId])}}')">
        编辑
    </button>
    <button class="btn btn-sm btn-primary"
            data-target="#mySimpleModal" data-toggle="modal"
            onclick="showSimpleModal('{{ route('backstage.permissions.create', ['parent_id' => $permission->id])}}')">
        添加子权限
    </button>
    @if($permission->children->isEmpty())
        <button class="btn btn-sm btn-danger"
                onclick="deletePermission('{{route('backstage.permissions.destroy', ['permission' => $permission->hashId])}}', '{{".permission_content_" . $permission->hashId}}', true)">删除</button>
    @endif
</td>