@extends('admin.page')
@section('card-title')
    @if(request('action') =='create') New Client @elseif(request('action') =='edit') Edit Client @else Client Listing @endif
@endsection
@section('card-options')
    @if(request('action') == 'listing' || request('action') =='create' || request('action') =='edit')
        <a href="{{ route('support',['section' => 'clients']) }}"
           class="btn btn-outline-primary btn-sm">
            Back
        </a>
    @else
        @if(user()->club =='*')
            <a href="{{ route('support',['section' => 'clients','action' => 'create']) }}"
               class="btn btn-outline-primary btn-sm">
                Create New
            </a>
        @endif
    @endif
@endsection
@section('page')
    @if(request('action') =='create' || request('action') =='edit' )
        <small>
            Please fill the details below.
        </small>
        <br>
        <form action="{{ route('support',['section' => 'clients','action' => request('action')]) }}" method="post">
            @csrf
            <br>
            <input name="client" value="{{ $client->id }}" type="hidden">
            <div class="row">
                <div class="col-4">
                    <label>Name</label>
                    <input type="text" name="name" value="{{ old('name',$client->name) }}"
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
                    <input type="text" name="email" value="{{ old('email',$client->email) }}"
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
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="active">Active</option>
                        <option @if(old('status',$client->status) == 'suspended') selected @endif value="suspended">
                            Suspended
                        </option>
                    </select>
                    @if ($errors->has('status'))
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $errors->first('status') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="col-2">
                    <label>% Profit to Receive</label>
                    <input type="number" name="commission" value="{{ old('commission',$client->commission) }}"
                           class="form-control"
                           placeholder="% Profit to Receive">
                    @if ($errors->has('profits'))
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $errors->first('profits') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="col-auto">
                    <label>&nbsp;</label>

                    <label class="custom-control custom-checkbox mt-1">
                        <input type="checkbox" class="custom-control-input"  name="client_deposit_total" @if(old('client_deposit_total',$client->client_deposit_total) == true) checked="" @endif>
                        <span class="custom-control-label">Include in Client Deposit Total</span>
                    </label>
                </div>
            </div>
{{--            <div class="row mt-3">--}}
{{--                <div class="col-5">--}}
{{--                    <label>Wallet Address</label>--}}
{{--                    <input type="text" name="wallet" value="{{ old('wallet',$client->wallet) }}"--}}
{{--                           class="form-control"--}}
{{--                           placeholder="Wallet Address">--}}
{{--                    @if ($errors->has('wallet'))--}}
{{--                        <span class="invalid-feedback d-block" role="alert">--}}
{{--                            <strong>{{ $errors->first('wallet') }}</strong>--}}
{{--                        </span>--}}
{{--                    @endif--}}
{{--                </div>--}}
{{--            </div>--}}

            <div class="row mt-3">
                <div class="col-8">
                    <div class="form-group">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" rows="6"
                                  placeholder="Content..">{{ old('notes',$client->notes) }}</textarea>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-3">
                    <button class="btn btn-outline-primary">Submit Details</button>
                    @if($client->exists)
                        <button type="button" class="btn btn-outline-danger"
                                onclick="if(confirm('This action is not reversible, Are you sure?'))  { event.preventDefault(); document.getElementById('delete-form').submit()}">
                            Delete Account
                        </button>
                    @endif
                </div>
            </div>
        </form>
        <form id="delete-form"
              action="{{ route('support',['section' => 'clients','action' => 'delete','client' => $client]) }}"
              method="POST">
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
                <th>Profit %</th>
                <th class="text-left">Joined</th>
                <th class=""></th>
            </tr>
            </thead>
            <tbody>
            @foreach($clients as $client)
                <tr>
                    <td class="text-center">
                        <div class="avatar d-block" style="background-image: url({{ $client->photo }})">
                        </div>
                    </td>
                    <td><a data-turbolinks="false" href="{{ route('client', compact('client')) }}"> {{$client->name}}</a></td>
                    <td>{{$client->email}}</td>
                    <td>{{currency( $client->commission)}}</td>
                    <td class="text-left">{{$client->created_at->format('jS M, Y')}}</td>
                    <td>
                        <div class="item-action dropdown">
                            <a href="javascript:void(0)" data-toggle="dropdown" class="icon" aria-expanded="false"><i
                                        class="fe fe-more-vertical"></i></a>
                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end"
                                 style="position: absolute; transform: translate3d(15px, 20px, 0px); top: 0px; left: 0px; will-change: transform;">
                                @if(user()->id!=4)
                                    <a href="{{ route('support',['action' => 'edit','section' => 'clients','client' => $client]) }}"
                                       class="dropdown-item"><i class="dropdown-icon fe fe-edit-2"></i> Edit </a>
                                    <a href="#" data-toggle="modal" data-target="#passwordReset"
                                       data-name="{{$client->name}}" data-email="{{$client->email}}"
                                       data-url="{{ route('support',['action' => 'reset_password','section' => 'clients','client' => $client]) }}"
                                       class="dropdown-item"><i class="dropdown-icon fe fe-lock"></i> Reset Password</a>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="modal fade" id="passwordReset" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form method="POST" action="">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Password Reset</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="email" class="col-form-label">Email:</label>
                                <p class="form-control"></p>
                            </div>
                            @csrf
                            <div class="form-group">
                                <label class="col-form-label">New Password:</label>
                                <input required type="password" name="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">Password Confirmation:</label>
                                <input type="password" name="password-confirmation" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    <script>
        function changePassword() {
            $('#passwordReset').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var client = button.data('name');
                var email = button.data('email');
                var modal = $(this);
                modal.find('.modal-title').text('Password Reset (' + client + ')');
                modal.find('.modal-body p').html(email);
                modal.find('.modal-body input.form-control').val('');
                modal.find('.modal-body input.form-control').on("change paste keyup", function () {
                    checkInputs()
                });
                modal.find('form').attr('action', button.data('url'));

                function checkInputs() {
                    if ($(modal.find('.modal-body input.form-control')[0]).val() === $(modal.find('.modal-body input.form-control')[1]).val()) {
                        modal.find('form button[type=submit]').removeAttr('disabled');
                    } else {
                        modal.find('form button[type=submit]').attr('disabled', "");
                    }
                }

                checkInputs();
            })
        }

        changePassword()
    </script>
@endsection
