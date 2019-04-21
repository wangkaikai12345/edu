<footer class="page-footer font-small unique-color-dark">

{{--{{ dd($index->site()) }}--}}
    <!-- Footer Links -->
    <div class="container text-center text-md-left mt-5">
{{--{{ dd($index->footer_nav()) }}--}}
        <!-- Grid row -->
        <div class="row mt-3">

            <!-- Grid column -->
            <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">

                <!-- Content -->
                <img src="{{ $index->site()['logo'] }}" height="60" alt="eduplay logo">


            </div>
            <!-- Grid column -->
        @foreach($index->footer_nav() as $foot)
         <!-- Grid column -->
            <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">

                <!-- Links -->
                <h6 class="text-uppercase font-weight-bold">{{ $foot['name'] }}</h6>
                <hr class="deep-purple accent-2 mb-4 mt-0 d-inline-block mx-auto" style="width: 60px;">

                @foreach($foot['children'] ?? [] as $child)
                <p>
                    <a href="#!">{{ $child['name'] }}</a>
                </p>
                @endforeach

            </div>
        @endforeach

            <!-- Grid column -->
        </div>
        <!-- Grid row -->

    </div>
    <!-- Footer Links -->

    <!-- Copyright -->
    <div class="footer-copyright text-center py-3">{{ $index->site()['banquan'] ?? ''}}

        <a href="https://mdbootstrap.com/education/bootstrap/">&nbsp;{{ $index->site()['domain'] }}</a>
    </div>
    <!-- Copyright -->

</footer>