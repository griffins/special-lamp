@extends('admin.page')
@section('card-title')
    @if(request('action') =='create') New Administrator @elseif(request('action') =='edit') Edit Administrator @else Administrator Listing @endif
@endsection
@section('card-options')
    @if(request('action') == 'listing' || request('action') =='create' || request('action') =='edit')
        <a href="{{ route('support',['section' => 'users']) }}"
           class="btn btn-outline-primary btn-sm">
            Back
        </a>
    @else
        <a href="{{ route('support',['section' => 'users','action' => 'create']) }}"
           class="btn btn-outline-primary btn-sm">
            Create New
        </a>
    @endif
@endsection
@section('page')
    @if(request('action') =='create' || request('action') =='edit' )
        <small>
            Please fill the details below.
        </small>
        <br>
        <form action="{{ route('support',['section' => 'users','action' => request('action')]) }}" method="post">
            @csrf
            <br>
            <input name="user" value="{{ $user->id }}" type="hidden">
            <div class="row">
                <div class="col-4">
                    <label>Name</label>
                    <input type="text" name="name" value="{{ old('name',$user->name) }}"
                           class="form-control"
                           placeholder="Name">
                    @if ($errors->has('name'))
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="col-4">
                    <label>Email</label>
                    <input type="text" name="email" value="{{ old('email',$user->email) }}"
                           class="form-control"
                           placeholder="Email">
                    @if ($errors->has('email'))
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-3">
                    <label>Country</label>
                    <select name="country_code" class="form-control">
                        <option>Select Country</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->alpha2 }}"
                                    @if(old('country_code',$user->country_code)== $country->alpha2) selected @endif> {{ $country->name }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('country_code'))
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $errors->first('country_code') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="col-5">
                    <label>Phone Number</label>
                    <input type="text" name="phone_number" value="{{ old('phone_number',$user->phone) }}"
                           class="form-control"
                           placeholder="Phone Number">
                    @if ($errors->has('phone_number'))
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $errors->first('phone_number') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-3">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="active">Active</option>
                        <option @if(old('status',$user->status) == 'suspended') selected @endif value="suspended">Suspended</option>
                    </select>
                    @if ($errors->has('status'))
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $errors->first('status') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-3">
                    <button class="btn btn-outline-primary">Submit</button>
                    @if($user->exists)
                        <button type="button" class="btn btn-outline-danger"
                                onclick="if(confirm('Are you sure?'))  { event.preventDefault(); document.getElementById('delete-form').submit()}">
                            Delete
                        </button>
                    @endif
                </div>
            </div>
        </form>
        <form id="delete-form"
              action="{{ route('support',['section' => 'users','action' => 'delete','user' => $user]) }}" method="POST">
            @csrf
        </form>
    @else
        <div class="s">

        </div>
        <table class="table table-striped mt-3">
            <thead>
            <tr>
                <th class="w-1"></th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th class="text-center">Country</th>
                <th class="text-center">Joined</th>
                <th class=""></th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td class="text-center">
                        <div class="avatar d-block" style="background-image: url({{ $user->photo }})">
                        </div>
                    </td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td>{{$user->phone}}</td>
                    <td class="text-center">
                        <i class="flag flag-{{ strtolower($user->country_code) }}"></i>
                    </td>
                    <td class="text-left">{{$user->created_at->format('jS M, Y')}}</td>
                    <td>
                        <a class="icon"
                           href="{{ route('support',['action' => 'edit','section' => 'users','user' => $user]) }}">
                            <i class="fe fe-edit"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
@endsection
