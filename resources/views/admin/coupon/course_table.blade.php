<div class="modal fade " id="plansModal" aria-labelledby="plansModalLabel" role="dialog"
     tabindex="-1"
     style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-simple modal-lg">
        <form class="modal-content form-horizontal" id="plansModal" autocomplete="off" action="javaScript:">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="plansModal">选择课程</h4>
            </div>
            <div class="search-course">
                <div class="panel">
                    <div class="row" style="padding: 0 20px">
                        <div class="form-group col-md-3">
                            <input type="text" class="form-control" name="title"
                                   placeholder="课程标题"
                                   autocomplete="off" value="">
                        </div>
                        <div class="form-group col-md-3">
                            <select class="form-control" name="status">
                                <option value="0">
                                    <font style="vertical-align: inherit;">
                                        <font style="vertical-align: inherit;">
                                            课程状态
                                        </font>
                                    </font>
                                </option>
                                @foreach($status as $key => $value)
                                    <option value="{{ $key }}"
                                            @if(request('status') == $key) selected @endif>
                                        <font style="vertical-align: inherit;">
                                            <font style="vertical-align: inherit;">
                                                {{ $value }}
                                            </font>
                                        </font>
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row text-right">
                        <div class="col-md-12" style="padding-right: 30px;padding-bottom: 5px">
                            <button class="btn btn-sm btn-primary" id="search_course_btn">搜索</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-hover  toggle-circle"
                       data-paging="false" data-plugin="selectable" data-row-selectable="true" id="messageTable">
                    <thead>
                    <tr>
                        <th>课程标题</th>
                        <th>价格</th>
                        <th>版本数</th>
                        <th>课程状态</th>
                        <th>学员数</th>
                        <th>创建者</th>
                        <th>连载状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody class="table-tbody-content">

                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>
