<link rel="stylesheet" href="{{ mix('/css/front/footer/index.css') }}">

<footer class="footer bg-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-5 mb-lg-0 mt-4">
                <a href="">
                    <img src="{{ http_format($index->site()['logo']) }}" alt="" class="footer_logo" width="200">
                </a>
            </div>
            @foreach($index->footer_nav() as $foot)
            <!-- Grid column -->
                <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4 mt-4 text-center">

                    <!-- Links -->
                    <h6 class="text-uppercase font-weight-bold mb-0">
                        <a href="{{ $foot['link'] }}"
                           class="important_drak"
                           target="{{ $foot->target ?  '_blank' : ''}}"
                        >{{ $foot['name'] }}</a>
                    </h6>
                    <hr class="deep-purple accent-2 mb-0 mt-0 d-inline-block mx-auto" style="width: 60px;">

                    <ul class="list-unstyled">
                    @foreach($foot['children'] ?? [] as $child)
                        <li>
                            <a href="{{ $child['link'] }}"
                                target=" {{ $child->target ?  '_blank' : ''}}">{{ $child['name'] }}</a>
                        </li>
                    @endforeach
                    </ul>
                </div>

            @endforeach

        </div>
        <div class="row align-items-center justify-content-md-between py-4 mt-4 delimiter-top text-center">
            <div class="col-md-12">
                <div class="copyright text-sm font-weight-bold text-center">
                    © {{ $index->site()['banquan'] ?? ''}} <a href="" class="font-weight-bold" target="_blank">{{ $index->site()['domain'] }}</a>
                </div>
            </div>
        </div>
    </div>
</footer>

{!! $index->site()['stat_code'] !!}