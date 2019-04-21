<td>{{ $permission->title }}</td>
<td>
    <button class="btn btn-sm btn-primary" data-target="#mySimpleModal" data-toggle="modal"
            onclick="showSimpleModal('{{ route('backstage.permissions.edit', ['permission' => $permission->hashId])}}')">
        编辑
    </button>
    <button class="btn btn-sm btn-danger"
            onclick="deletePermission('{{route('backstage.permissions.destroy', ['permission' => $permission->hashId])}}', '{{"#children_content_" . $permission->hashId}}', false)">删除
    </button>
</td>
