@extends('layouts.main')
@section('head')
    <script src="https://cdn.jsdelivr.net/npm/d3@5.9.2/dist/d3.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/c3@0.7.0/c3.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/seedrandom/3.0.1/seedrandom.min.js">
    </script>
    <script>
        Math.seed = function (s) {
            return function () {
                s = Math.sin(s) * 10000;
                return s - Math.floor(s);
            };
        };
        var randomProperty = function (obj, s) {
            var keys = Object.keys(obj);
            return obj[keys[keys.length * Math.seed(s) << 0]];
        };
        window.colors = {
            'blue': '#467fcf',
            'blue-darkest': '#0e1929',
            'purple': '#a55eea',
            'purple-darkest': '#21132f',
            'purple-darker': '#42265e',
            'purple-dark': '#844bbb',
            'purple-light': '#c08ef0',
            'red': '#e74c3c',
            'red-darkest': '#2e0f0c',
            'orange': '#fd9644',
            'orange-darkest': '#331e0e',
            'orange-light': '#feb67c',
            'yellow': '#f1c40f',
            'lime': '#7bd235',
            'lime-darkest': '#192a0b',
            'teal': '#2bcbba',
            'teal-darkest': '#092925',
            'teal-dark': '#22a295',
            'cyan-darkest': '#052025',
            'cyan-darker': '#09414a',
            'cyan-dark': '#128293',
            'cyan-light': '#5dbecd',
            'gray': '#868e96',
            'gray-darkest': '#1b1c1e',
            'gray-darker': '#36393c',
            'gray-dark': '#343a40',
            'gray-dark-darkest': '#0a0c0d',
            'gray-dark-darker': '#15171a',
            'gray-dark-dark': '#2a2e33',
        };
    </script>
@endsection
@section('title')
    @if(user()->role =='admin') Client @else My  @endif Dashboard / {{ $client->club }} / {{ $client->name }}
    <a href="{{ route('client',['client' => $client]) }}"
       class="mt-1 ml-3 btn btn-primary float-right">Go Back</a>
    <div class="dropdown float-right">
{{--        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">--}}
{{--            <i class="fe fe-calendar mr-2"></i>{{ ucfirst(request('type','daily')) }}--}}
{{--        </button>--}}
{{--        <div class="dropdown-menu">--}}
{{--            <a class="dropdown-item" data-turbolinks="false"--}}
{{--               href="{{ route('client.dashboard',['client' => $client,'type' =>'daily']) }}">Daily</a>--}}
{{--            <a class="dropdown-item" data-turbolinks="false"--}}
{{--               href="{{ route('client.dashboard',['client' => $client,'type' =>'weekly']) }}">Weekly</a>--}}
{{--            <a class="dropdown-item" data-turbolinks="false"--}}
{{--               href="{{ route('client.dashboard',['client' => $client,'type' =>'monthly']) }}">Monthly</a>--}}
{{--        </div>--}}
    </div>
@endsection
@section('content')
    <h1 class="page-title w-100 text-white">
        @if(user()->role =='admin') Client @else My  @endif Accounts
    </h1>
    @foreach($currencies as $currency)
        <div class="row mt-3">
            @foreach($periods as $x => $period)
                <div class="col-6 col-sm-4 col-lg-auto" title="{{ date_range($period->start,$period->end) }}">
                    <div class="card p-3">
                        <div class="d-flex align-items-center">
                            <div>
                                <h4 class="m-0">
                                    {{ currency((clone $currency['transactions'])->whereBetween('closed_at',[$period->start,$period->end])->profit(),true,2,!true) }}
                                    <small> {{$currency['code']}}</small>
                                </h4>
                                <small class="text-muted"> Profit ({{ $period->name }})</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="col-6 col-sm-4 col-lg-auto  ">
                <div class="card p-3">
                    <div class="d-flex align-items-center">
                        <div>
                            <h4 class="m-0">
                                <a href="javascript:void(0)" data-toggle="modal"
                                   data-target="#model-{{ $currency['code'] }}"
                                   style="text-decoration: none">
                                    {{ currency((clone $currency['transactions'])->balance(),true,2,!true) }}
                                    <small> {{$currency['code']}}</small>
                                </a>
                            </h4>
                            <a href="javascript:void(0)" data-toggle="modal"
                               data-target="#model-{{ $currency['code'] }}" style="text-decoration: none">
                                <small class="text-muted">Balance of Deposits</small>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="model-{{ $currency['code'] }}" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">{{ $currency['code']  }} Balance</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-6 col-sm-4 col-lg-auto">
                                        <div class="card p-3">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h4 class="m-0">
                                                        {{ currency((clone $currency['transactions'])->deposits()->sum('profit'),true,2,!true) }}
                                                        <small> {{$currency['code']}}</small>
                                                    </h4>
                                                    <small class="text-muted">Deposits</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-4 col-lg-auto">
                                        <div class="card p-3">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h4 class="m-0">
                                                        {{ currency((clone $currency['transactions'])->withdrawals()->sum('profit'),true,2,!true) }}
                                                        <small> {{$currency['code']}}</small>
                                                    </h4>
                                                    <small class="text-muted"> Withdraws</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-4 col-lg-auto">
                                        <div class="card p-3">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h4 class="m-0">
                                                        {{ currency((clone $currency['transactions'])->balance(),true,2,!true) }}
                                                        <small> {{$currency['code']}}</small>
                                                    </h4>
                                                    <small class="text-muted"> Balance </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(!$loop->last)
                <hr class="mt-0 mb-5">
            @endif
        </div>
    @endforeach
    <div class="row row-cards mt-3">
        @foreach($accounts->cursor() as $account)
            {{--            @include('client.line_chart',['account' =>$account])--}}
        @endforeach
    </div>
    @if($referrals->count()>0)
        <h1 class="page-title w-100 text-white">
           @if(user()->role =='admin') Client @else My  @endif Referrals
        </h1>
        @foreach($r_currencies as $currency)
            <div class="row mt-3">
                @foreach($periods as $period)
                    <div class="col-6 col-sm-4 col-lg-2">
                        <div class="card p-3">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h4 class="m-0">
                                        {{ currency((clone $currency['transactions'])->whereBetween('closed_at',[$period->start,$period->end])->profit(),true,2,!true) }}
                                        <small> {{$currency['code']}}</small>
                                    </h4>
                                    <small class="text-muted"> Profit ({{ $period->name }})</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
        <div class="row row-cards mt-3">
            @foreach($referrals->cursor() as $account)
                {{--                    @include('client.line_chart',['account' =>$account])--}}
            @endforeach
        </div>
    @endif
@endsection