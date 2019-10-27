@extends('layouts.single')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col col-md-4 col-sm-12 mx-auto">
                <div class="text-center mb-6">
                    <img src="{{ asset('images/logo.svg') }}" class="h-6" alt="">
                </div>
                <div class="card">
                    <form method="POST" action="{{ route('login') }}" class="card-body">
                        @csrf
                        <div class="form-group m-5 row">
                            <div class="media">
                                <img class="mr-3" src="{{ $client->photo }}" style="width: 50px">
                                <div class="media-body">
                                    <h5>{{ $client->name }}
                                    </h5>
                                    <small>{{$client->phone}}</small>
                                    <a class="btn pl-0"
                                       href="{{ route('password.request', ['guard' => $client->role]) }}">
                                        {{ __('I forgot my Password') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="email" value="{{ $client->email }}">
                        <input type="hidden" name="guard" value="{{ $client->role}}">
                        <div class="form-group m-5 row">
                            <div class="col-12">
                                <input id="password" type="password"
                                       class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                       name="password" value="" required autofocus>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-12 mt-3">
                                <a class="btn pl-0" href="{{ route('login') }}">
                                    {{ __('Not Me') }}
                                </a>
                                <button class="float-right btn btn-primary">Next</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
