@extends('frontend.default.manage.course.index')
@section('title', '课程 基本信息')

@section('partStyle')

@endsection

@section('rightBody')
    <div class="col-xl-9 profile">
        <div class="card">
            <div class="card-body">
                    <h6 class="card-title">基本信息</h6>
                    <hr class="pb-3">
                    <!-- Horizontal material form -->
                    <form action="{{ route('manage.courses.update', $course) }}" method="post">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="form-group row col-xl-10 pl-0 mb-4">
                            <label for="title" class="col-sm-2 col-form-label pr-0">标题</label>
                            <div class="col-sm-10">
                                <input type="text"
                                       class="form-control"
                                       id="title" placeholder="请输入标题"
                                       value="{{ $course['title'] }}"
                                       name="title"
                                >
                            </div>
                        </div>

                        <div class="form-group row col-xl-10 pl-0 mb-4">
                            <label for="subtitle" class="col-sm-2 col-form-label pr-0">副标题</label>
                            <div class="col-sm-10">
                                <textarea id="subtitle" name="subtitle" class="form-control md-textarea" length="120" rows="3"  placeholder="请输入副标题">{{ $course['subtitle'] }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row col-xl-10 pl-0 mb-4">
                            <label for="tags" class="col-sm-2 col-form-label pr-0">标签</label>
                            <div class="col-sm-10">
                                <div class="treeSelector" id="tags"></div>
                            </div>
                        </div>

                        <div class="form-group row col-xl-10 pl-0 mb-4">
                            <label for="classify" class="col-sm-2 col-form-label pr-0">分类</label>
                            <div class="col-sm-10">
                                <select class="mdb-select md-form m-0" id="classify">
                                    <optgroup label="前端">
                                        <option value="1">HTML</option>
                                        <option value="2">CSS</option>
                                    </optgroup>
                                    <optgroup label="后端">
                                        <option value="3">JAVA</option>
                                        <option value="4">PHP</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row col-xl-10 pl-0 mb-4">
                            <label for="serialize_mode" class="col-sm-2 col-form-label pr-0">连载状态</label>
                            <div id="serialize_mode" class="col-sm-9 mt-2">
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" id="materialInline1" name="serialize_mode" value="none">
                                    <label class="form-check-label" for="materialInline1">非连载课程</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" id="materialInline2" value="serialized" name="serialize_mode">
                                    <label class="form-check-label" for="materialInline2">更新中</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" id="materialInline3" value="finished" name="serialize_mode">
                                    <label class="form-check-label" for="materialInline3">已完结</label>
                                </div>
                            </div>
                        </div>

                        <button type="submit">保存</button>

                    </form>
                    <!-- Horizontal material form -->
                </div>
        </div>
    </div>
@endsection

@section('partScript')

@endsection