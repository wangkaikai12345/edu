@extends('frontend.default.plan.show')
@section('title', '版本学习-介绍')
@section('leftBody')
    <div class="tab-pane">
        <div>
            <div class="es_piece">
                <div class="piece_header">版本介绍</div>
                <div class="piece_body" style="text-align: left;">
                   {{ $plan['about'] }}
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
                        @if ($course['goals'])
                            @foreach($course['goals'] as $goal)
                                <li>
                                    {{ $goal }}
                                </li>
                            @endforeach
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
                        @if ($course['audiences'])
                            @foreach($course['audiences'] as $audience)
                                <li>
                                    {{ $audience }}
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
                <div class="goals">
                    <ul></ul>
                </div>
            </div>
        </div>
    </div>
@endsection







