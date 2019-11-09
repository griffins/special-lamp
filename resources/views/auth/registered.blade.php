@extends('layouts.single')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col col-login mx-auto">
                <div class="card">
                    <div class="card-header">{{ __('Your details have been received.') }}</div>
                    <div class="card-body">
                        <div class="alert alert-success" role="alert">
                            {{ __('We will review your details and get back to you shortly') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
