{{--mdb那一套--}}
{{--@if ($paginator->hasPages())--}}
{{--<ul class="pagination pagination-circle pg-blue justify-content-center">--}}
    {{-- Previous Page Link --}}
    {{--@if ($paginator->onFirstPage())--}}
        {{--<li class="page-item disabled"><a class="page-link">&laquo;</a></li>--}}
    {{--@else--}}
        {{--<li class="page-item">--}}
            {{--<a class="page-link" aria-label="Previous" href="{{ $paginator->previousPageUrl() }}">--}}
                {{--<span aria-hidden="true">&laquo;</span>--}}
                {{--<span class="sr-only">上一页</span>--}}
            {{--</a>--}}
        {{--</li>--}}
    {{--@endif--}}

    {{-- Pagination Elements --}}
    {{--@foreach ($elements as $element)--}}
        {{-- "Three Dots" Separator --}}
        {{--@if (is_string($element))--}}
            {{--<li class="disabled"><span>{{ $element }}</span></li>--}}
        {{--@endif--}}

        {{-- Array Of Links --}}
        {{--@if (is_array($element))--}}
            {{--@foreach ($element as $page => $url)--}}
                {{--@if ($page == $paginator->currentPage())--}}
                    {{--<li class="page-item active"><a class="page-link">{{ $page }}</a></li>--}}
                {{--@else--}}
                    {{--<li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>--}}
                {{--@endif--}}
            {{--@endforeach--}}
        {{--@endif--}}
    {{--@endforeach--}}

    {{-- Next Page Link --}}
    {{--@if ($paginator->hasMorePages())--}}
        {{--<li class="page-item">--}}
            {{--<a class="page-link" aria-label="Next" href="{{ $paginator->nextPageUrl() }}">--}}
                {{--<span aria-hidden="true">&raquo;</span>--}}
                {{--<span class="sr-only">下一页</span>--}}
            {{--</a>--}}
        {{--</li>--}}
    {{--@else--}}
        {{--<li class="page-item"><a class="page-link">&raquo;</a></li>--}}
    {{--@endif--}}
{{--</ul>--}}
{{--@endif--}}

{{--默认一套--}}
@if ($paginator->hasPages())
    <ul class="pagination justify-content-center">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled"><span class="page-link"><i class="iconfont">&#xe9d2;</i></span></li>
        @else
            <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev"><i class="iconfont">&#xe9d2;</i></a></li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next"><i class="iconfont">&#xe632;</i></a></li>
        @else
            <li class="page-item disabled"><span class="page-link"><i class="iconfont">&#xe632;</i></span></li>
        @endif
    </ul>
{{--新款ui一套--}}
    {{--<ul class="pagination">--}}
        {{--<li class="page-item"><a class="page-link" href="#"><i class="iconfont">&#xe9d2;</i></a></li>--}}
        {{--<li class="page-item"><a class="page-link" href="#">1</a></li>--}}
        {{--<li class="page-item"><a class="page-link" href="#">2</a></li>--}}
        {{--<li class="page-item active"><a class="page-link" href="#">3</a></li>--}}
        {{--<li class="page-item"><a class="page-link" href="#">4</a></li>--}}
        {{--<li class="page-item"><a class="page-link" href="#">5</a></li>--}}
        {{--<li class="page-item"><a class="page-link" href="#">6</a></li>--}}
        {{--<li class="page-item"><a class="page-link" href="#"><i class="iconfont">&#xe632;</i></a></li>--}}
    {{--</ul>--}}
@endif
