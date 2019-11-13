@extends( 'layouts.reports')
@section('title')
    {{$report->title}}
    <a href="{{ route('report') }}"
       class="btn btn-info float-right">Back</a>
@endsection
@section('content')
    <div class="card">
        <table class="table card-table table-striped table-bordered datatable">
            <thead>
            <tr>
                <th>#</th>
                <th>Client</th>
                <th>Deposits</th>
                <th>Profit %</th>
                <th>Balance</th>
            </tr>
            </thead>
            @foreach($query->get() as $x => $client)
                <tr>
                    <td>{{ $x+1 }}</td>
                    <td>{{ $client->name }}</td>
                    <td>{{  currency( $client->investorTransactions()->deposits() - $client->investorTransactions()->withdrawals()) }}</td>
                    <td>{{  currency( $client->commission) }}</td>
                    <td>{{  currency( $client->balance) }}</td>
                </tr>
            @endforeach
            <tfoot>
            </tfoot>
        </table>
    </div>
@endsection
