<div class="panel-group permission_content_{{$permission->hashId}}" id="exampleAccordionDefault" aria-multiselectable="true"
     role="tablist">
    <div class="panel">
        <div class="panel-heading" id="exampleHeadingDefault{{$permission->id}}"
             role="tab">
            <a class="panel-title collapsed" data-toggle="collapse"
               href="#exampleCollapseDefault{{$permission->id}}"
               data-parent="#exampleAccordionDefault"
               aria-expanded="false" aria-controls="exampleCollapseDefaultOne">
                <div>
                    权限组名: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $permission->title }}
                </div>

            </a>
        </div>
        <div class="panel-collapse collapse"
             id="exampleCollapseDefault{{$permission->id}}"
             aria-labelledby="exampleHeadingDefaultOne" role="tabpanel" style="">
            <div class="panel-body">

                <table class="table table-hover  toggle-circle"
                       data-paging="false">
                    <thead>
                    <tr>
                        <th>名称</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody id="children_{{$permission->hashId}}">
                    <tr id="children_content_{{$permission->hashId}}">
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
                    </tr>
                    @foreach($permission->children as $child)
                        <tr id="children_content_{{$child->hashId}}">
                            <td>{{$child->title}}</td>
                            <td>
                                <button class="btn btn-sm btn-primary"
                                        data-target="#mySimpleModal" data-toggle="modal"
                                        onclick="showSimpleModal('{{ route('backstage.permissions.edit', ['permission' => $child->hashId])}}')">
                                    编辑
                                </button>
                                <button class="btn btn-sm btn-danger">删除</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>