@extends('admin.page')
@section('card-title')
    @if(request('action') =='create') New Account @elseif(request('action') =='edit') Edit Account @else Account Listing @endif
@endsection
@section('card-options')
    @if(request('action') == 'listing' || request('action') =='create' || request('action') =='edit')
        <a href="{{ route('support',['section' => 'accounts']) }}"
           class="btn btn-outline-primary btn-sm">
            Back
        </a>
    @else
        @if(user()->club =='*')
            <a href="{{ route('support',['section' => 'accounts','action' => 'create']) }}"
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
        <form action="{{ route('support',['section' => 'accounts','action' => request('action')]) }}" method="post">
            @csrf
            <br>
            <input name="account" value="{{ $account->id }}" type="hidden">
            <div class="row">
                <div class="col-4">
                    <label>Name</label>
                    <input type="text" name="name" value="{{ old('name',$account->name) }}"
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
                    <input type="text" name="email" value="{{ old('email',$account->email) }}"
                           class="form-control"
                           placeholder="Email">
                    @if ($errors->has('email'))
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-4">
                    <label>Password</label>
                    <input type="text" name="password" value="{{ old('password',$account->password) }}"
                           class="form-control"
                           placeholder="password for email account">
                    @if ($errors->has('password'))
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <br>
            <div class="row">
                @if($account->exists)
                    <div class="col-4">
                        <label>Wallet Address QR</label>
                        <div class="card card-profile">
                            <div class="card-body text-center">
                    <span class="img rounded-0 avatar "
                          style="background-image: url({{ $account->photo }});width:200px; height:200px">
  <a href="javascript:void(0)" onclick="changeProfile()" class="avatar-status fe fe-camera"
     style="background: transparent; font-size: 16px;text-decoration: none">
  </a>

</span>


                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-4">
                    <label>Wallet Address</label>
                    <input type="text" name="wallet" value="{{ old('wallet',$account->wallet) }}"
                           class="form-control"
                           placeholder="wallet for account">
                    @if ($errors->has('wallet'))
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $errors->first('wallet') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-3">
                    <button class="btn btn-outline-primary">Submit Details</button>
                    @if($account->exists)
                        <button type="button" class="btn btn-outline-danger"
                                onclick="if(confirm('This action is not reversible, Are you sure?'))  { event.preventDefault(); document.getElementById('delete-form').submit()}">
                            Delete Account
                        </button>
                    @endif
                </div>
            </div>
        </form>
        <form id="delete-form"
              action="{{ route('support',['section' => 'accounts','action' => 'delete','client' => $account]) }}"
              method="POST">
            @csrf
        </form>
        <form method="post" id="wallet-form"
              action="{{ route('support',['section' =>'accountQr','account' => $account]) }}"
              enctype="multipart/form-data">
            @csrf
            <input name="image" id="profile" type="file" style="display: none"
                   accept="image/png, image/jpeg">
        </form>
    @else
        <div class="s">

        </div>
        <table class="table table-striped mt-3">
            <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th class="text-left">Created</th>
                <th class=""></th>
            </tr>
            </thead>
            <tbody>
            @foreach($accounts as $k => $account)
                <tr>
                    <td>{{ $k+1 }}</td>
                    <td>@if(cache('default_wallet') == $account->id) <i style="font-size: larger;font-weight: bolder"  class=" text-danger fe fe-check-circle"></i>@endif  {{$account->name}}</td>
                    <td>{{$account->email}}</td>
                    <td class="text-left">{{$account->created_at->format('jS M, Y')}}</td>
                    <td>
                        <div class="item-action dropdown">
                            <a href="javascript:void(0)" data-toggle="dropdown" class="icon" aria-expanded="false"><i
                                    class="fe fe-more-vertical"></i></a>
                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end"
                                 style="position: absolute; transform: translate3d(15px, 20px, 0px); top: 0px; left: 0px; will-change: transform;">
                                @if(user()->id!=4)
                                    <a href="{{ route('support',['action' => 'edit','section' => 'accounts','account' => $account]) }}"
                                       class="dropdown-item"><i class="dropdown-icon fe fe-edit-2">

                                        </i> Edit Account </a>
                                    <a href="{{ route('support',['action' => 'default','section' => 'accounts','account' => $account]) }}"
                                       class="dropdown-item"><i class="dropdown-icon fe fe-check-circle">

                                        </i> Set Default Wallet </a>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
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

        changePassword();

        function changeProfile() {
            $("#profile").trigger('click')
        }

        $(document).ready(function () {
            $("#profile").on('change', function () {
                document.getElementById('wallet-form').submit();
            });
        });
    </script>
@endsection
