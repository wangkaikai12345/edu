@extends('frontend.review.plan.layout')

@section('leftBody')
    <div class="tab-pane">
        <div class="es_piece">
            <div class="piece_header">版本介绍</div>
            <div class="piece_body" style="text-align: left;">
                {!! $plan['about']??'<span class="text_color_999">&nbsp;&nbsp;还没有介绍...</span>' !!}
            </div>
            <div class="goals">
                <ul></ul>
            </div>
            <div class="goals">
                <ul></ul>
            </div>
        </div>
        <div class="es_piece">
            <div class="piece_header">版本目标</div>
            <div class="piece_body" style="text-align: left;"></div>
            <div class="goals">
                <ul>
                    @if ($plan['goals'])
                        @foreach($plan['goals'] as $goal)
                            <li>
                                {{ $goal }}
                            </li>
                        @endforeach
                    @else
                        <span class="text_color_999">
                            还没有添加...
                        </span>
                    @endif
                </ul>
            </div>
            <div class="goals">
                <ul></ul>
            </div>
        </div>
        <div class="es_piece">
            <div class="piece_header">适应人群</div>
            <div class="piece_body" style="text-align: left;"></div>
            <div class="goals">
                <ul>
                    @if ($plan['audiences'])
                        @foreach($plan['audiences'] as $audience)
                            <li>
                                {{ $audience }}
                            </li>
                        @endforeach
                    @else
                        <span class="text_color_999">
                            还没有添加...
                        </span>
                    @endif
                </ul>
            </div>
            <div class="goals">
                <ul></ul>
            </div>
        </div>
    </div>
@endsection







