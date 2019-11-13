<div class="card">
    <div class="card-status bg-teal"></div>
    <div class="card-header">
        <h3 class="card-title">#{{ $client->name }} (USD - {{currency( normalize( $client->balance),true,2)}}
            )</h3>
        @if((user()->role =='admin'))
            <div class="card-options">
                <button data-toggle="modal" data-target="#transaction" data-type="deposit" class="btn btn-success">
                    Deposit
                </button>
                <button data-toggle="modal" data-target="#transaction" data-type="withdraw"
                        class="btn btn-primary mx-2">Withdraw
                </button>
            </div>
        @endif
    </div>
    <div class="card-body">
        <div class="row">
            @foreach($periods as $period)
                <div class="col-4">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="mb-1">{{ currency( normalize( $client->transactions()->where('type','profit')->whereBetween('date',[$period->start,$period->end])->profit()),true,2,false) }}</h3>
                            <div class="text-muted" title="{{ date_range($period->start,$period->end) }}">Profit
                                ({{ $period->name }})
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <div class="text-muted" title="Deposits">
                            Deposits
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>{{ currency( $client->transactions()->deposits(),true,2,false) }}</b>
                        </div>
                        <div class="text-muted mt-2" title="Deposits">
                            Withdrawals <b>{{ currency( $client->transactions()->withdrawals(),true,2,false) }}</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @php $transactions = $client->transactions()->with('account')->orderByDesc('date')->paginate(); @endphp
        @if($transactions->count()>0)
            <h5 id="transactions">Recent Transactions</h5>
            <table class="table table-striped">
                <thead>
                <tr>
                    <td><b>ID</b></td>
                    <td><b>Account</b></td>
                    <td><b>Type</b></td>
                    <td><b>Item</b></td>
                    <td><b>Amount</b></td>
                    <td><b>Date (GMT)</b></td>
                </tr>
                </thead>
                <tbody>
                @foreach($transactions as $transaction)
                    <tr>
                        <td>
                            <b>
                                <div class="wrap"> {{ strtoupper(($transaction->id)) }}</div>
                            </b>
                        </td>
                        <td><b>{{ strtoupper( $transaction->account->name)}}</b></td>
                        <td><b>{{ strtoupper( $transaction->type)}}</b></td>
                        <td><b>{{ strtoupper( $transaction->narration) }}</b></td>
                        <td><b>{{ currency($transaction->amount,true,2) }}</b></td>
                        <td><b>{{ $transaction->date }}</b></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $transactions->fragment('transactions')->render([]) }}
        @else
            <div class="jumbotron text-center">
                No transactions
            </div>
        @endif
        <br>
    </div>
    <div class="card-footer">
    </div>
</div>
@section('scripts')
    <script>
        function setupInvestor() {
            $('#edit-investor').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var client = button.data('name');
                var clientId = button.data('id');
                var email = button.data('email');
                var commission = button.data('commission');
                var modal = $(this);
                modal.find('.modal-title').text('Edit Investor (' + client + ')');
                modal.find('.modal-body input[name=email].form-control').val(email);
                modal.find('.modal-body input[name=name].form-control').val(client);
                modal.find('.modal-content input[name=investor_id]').val(clientId);
                modal.find('.modal-body input[name=commission].form-control').val(commission);
            });
            $('.assign').on('click', function () {
                var transaction = $(this);
                axios.post(transaction.data('url')).then(function (a, b) {
                    if (a.status === 200) {
                        transaction.closest('tr').remove();
                    }
                });
            });
        }

        setupInvestor()
    </script>
@append
