@extends('admin.page')
@section('card-title')
    Transaction Requests
@endsection
@section('card-options')
    @if(request('action') == 'listing' || request('action') =='create' || request('action') =='edit')
        <a href="{{ route('support',['section' => 'users']) }}"
           class="btn btn-outline-primary btn-sm">
            Back
        </a>
    @endif
@endsection
@section('page')
    <table class="table table-striped mt-3">
        <thead>
        <tr>
            <th class="w-1"></th>
            <th>Name</th>
            <th>Email</th>
            <th>Type</th>
            <th>Amount</th>
            <th class="text-left">Date</th>
            <th class=""></th>
        </tr>
        </thead>
        <tbody>
        @foreach($requests as $request)
            <tr>
                <td class="text-center">
                    <div class="avatar d-block" style="background-image: url({{ $request->client->photo }})">
                    </div>
                </td>
                <td><a href="{{ route('client', ['client' => $request->client]) }}"> {{$request->client->name}}</a></td>
                <td>{{$request->client->email}}</td>
                <td>{{ ucfirst( $request->operation) }}</td>
                <td>{{currency( $request->amount,true,8)}}</td>
                <td class="text-left">{{$request->created_at->diffForHumans()}}</td>
                <td>
                    <div class="item-action dropdown">
                        <a href="javascript:void(0)" data-toggle="dropdown" class="icon" aria-expanded="false"><i
                                    class="fe fe-more-vertical"></i></a>
                        <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end"
                             style="position: absolute; transform: translate3d(15px, 20px, 0px); top: 0px; left: 0px; will-change: transform;">
                            <a data-name="{{$request->client->name}}" data-email="{{$request->client->email}}"
                               href="#" data-toggle="modal" data-target="#reject"
                               data-url="{{ route('support',['action' => 'reject','section' => 'requests','client' => $request->client,'request' => $request]) }}"
                               class="dropdown-item"><i class="dropdown-icon fe fe-trash"></i> Reject </a>
                            <a href="#" data-toggle="modal" data-target="#confirm"
                               data-name="{{$request->client->name}}" data-amount="{{$request->amount}}" data-wallet="{{$request->wallet}}" data-type="{{ ucfirst( $request->operation) }}" data-txn="{{$request->transaction_id}}"
                               data-url="{{ route('support',['action' => 'confirm','section' => 'requests','client' => $request->client,'request' => $request]) }}"
                               class="dropdown-item"><i class="dropdown-icon fe fe-check-circle"></i> Confirm </a>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="modal fade" id="reject" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="POST" action="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Reject Transaction</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="email" class="col-form-label">Client:</label>
                            <p class="form-control"></p>
                        </div>
                        @csrf
                        <div class="form-group">
                            <label class="col-form-label">Reason:</label>
                            <textarea name="reason" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="POST" action="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Confirm Transaction</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="email" class="col-form-label">Client:</label>
                            <p id="client" class="form-control"></p>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-form-label">Wallet ID:</label>
                            <p id="wallet" class="form-control"></p>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-form-label">Transaction ID:</label>
                            <p id="txn" class="form-control"></p>
                        </div>
                        @csrf
                        <div class="form-group">
                            <label class="col-form-label">Amount:</label>
                            <input name="amount" value="" class="form-control">
                        </div>
                        <div class="form-group">
                            {{ date_picker('Transaction Date','date',now()->toDateTimeString()) }}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        function rejectRequest() {
            $('#reject').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var client = button.data('name');
                var modal = $(this);
                modal.find('.modal-title').text('Reject Request (' + client + ')');
                modal.find('.modal-body p').html(client);
                modal.find('form').attr('action', button.data('url'));
            })
        }

        rejectRequest();

        function confirmRequest() {
            $('#confirm').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var client = button.data('name');
                var amount = button.data('amount');
                var type = button.data('type');
                var txn = button.data('txn');
                var wallet = button.data('wallet');
                var modal = $(this);
                modal.find('.modal-title').text('Confirm ' + type + ' Request (' + client + ')');
                modal.find('#client').html(client);
                modal.find('#wallet').html(wallet);
                modal.find('#txn').html(txn);
                modal.find('.modal-body input[name=amount]').val(amount);
                modal.find('form').attr('action', button.data('url'));
            })
        }

        confirmRequest();
        rejectRequest()
    </script>
@endsection
