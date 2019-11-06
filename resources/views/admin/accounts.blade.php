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
            <input name="account_id" value="{{ $account->id }}" type="hidden">
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
                    <label>Account Number</label>
                    <input type="text" name="account" value="{{ old('account',$account->account) }}"
                           class="form-control"
                           placeholder="Account">
                    @if ($errors->has('account'))
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $errors->first('account') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <br>
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
              action="{{ route('support',['section' => 'accounts','action' => 'delete','account_id' => $account]) }}"
              method="POST">
            @csrf
        </form>
    @else
        <table class="table table-striped mt-3">
            <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Account</th>
                <th class="text-left">Created</th>
                <th class=""></th>
            </tr>
            </thead>
            <tbody>
            @foreach($accounts as $k => $account)
                <tr>
                    <td>{{ $k+1 }}</td>
                    <td>{{$account->name}}</td>
                    <td>{{$account->account}}</td>
                    <td class="text-left">{{$account->created_at->format('jS M, Y')}}</td>
                    <td>
                        <div class="item-action dropdown">
                            <a href="javascript:void(0)" data-toggle="dropdown" class="icon" aria-expanded="false"><i
                                    class="fe fe-more-vertical"></i></a>
                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end"
                                 style="position: absolute; transform: translate3d(15px, 20px, 0px); top: 0px; left: 0px; will-change: transform;">
                                @if(user()->id!=4)
                                    <a href="{{ route('support',['action' => 'edit','section' => 'accounts','account_id' => $account]) }}"
                                       class="dropdown-item"><i class="dropdown-icon fe fe-edit-2">
                                        </i> Edit Account </a>
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
