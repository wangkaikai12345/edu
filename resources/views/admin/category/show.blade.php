<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="mySimpleModal">分类</h4>
    </div>
    {{ csrf_field() }}
    <div class="modal-body">
        <table class="table  table-hover  toggle-circle"
               data-paging="false">
            <thead>
            <tr>
                <th>名称</th>
                <th>子类数量</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($categories as $category)
                <tr>
                    <td>
                        <a>
                            {{ $category->name }}
                        </a>
                    </td>
                    <td>{{ $category->children_count }}</td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-outline">
                                <font style="vertical-align: inherit;">
                                    <font style="vertical-align: inherit;">
                                        添加子类
                                    </font>
                                </font>
                            </button>
                            <a class="btn btn-default dropdown-toggle btn-outline"
                               id="exampleSplitDropdown1" data-toggle="dropdown"
                               aria-expanded="false"></a>
                            <div class="dropdown-menu" aria-labelledby="exampleSplitDropdown1" role="menu"
                                 x-placement="bottom-start"
                                 style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(57px, 39px, 0px);">
                                {{--<a class="dropdown-item" href="javascript:void(0)" role="menuitem"--}}
                                   {{--data-target="#mySimpleModal" data-toggle="modal"--}}
                                   {{--onclick="showSimpleModal('{{ route('backstage.categoryGroup.edit', ['categoryGroup' => $categoryGroup->hashId ])}}')"--}}
                                   {{--style="text-decoration:none ">--}}
                                    {{--<font style="vertical-align: inherit;">--}}
                                        {{--<font style="vertical-align: inherit;">--}}
                                            {{--编辑分类--}}
                                        {{--</font>--}}
                                    {{--</font>--}}
                                {{--</a>--}}
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
    </div>
</div>
