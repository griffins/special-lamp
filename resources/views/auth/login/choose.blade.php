@extends('layouts.single')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col col-md-4 col-sm-12 mx-auto">
                <div class="text-center mb-6">
                    <img src="{{ asset('images/logo.svg') }}" class="h-6" alt="">
                </div>
                <form class="card" action="{{ route('login.verify') }}" method="post">
                    <div class="card-body">
                        <div class="card-header mb-5">{{ __('Choose ID') }}</div>
                        @foreach($users as $client)
                            <a href="{{ route('login.verify',['email' => $client->email,'guard' => $client->role]) }}">
                                <div class="form-group">
                                    <div class="media">
                                        <img class="mr-3" src="{{ $client->photo }}" style="width: 50px">
                                        <div class="media-body">
                                            <h5>{{ $client->name }}<span class="ml-2 badge badge-primary">@if($client->role == 'admin')
                                                        Admin @else Client @endif</span></h5>
                                            <h6>{{$client->phone}}</h6>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                        <div class="form-group">
                            <a class="btn btn-outline-primary" href="{{ route('login') }}">
                                {{ __('Not Me') }}
                            </a>
                        </div>
                    </div>
                </form>
{{--                <div class="text-center text-muted">--}}
{{--                    Don't have account yet? <a href="{{ route('register') }}">Sign up</a>--}}
{{--                </div>--}}
            </div>
        </div>
    </div>
@endsection
