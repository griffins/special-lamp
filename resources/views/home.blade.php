@extends('layouts.main')
@section('title')
    Dashboard
@endsection
@section('content')
    <div class="row mt-3 mx-0">
        <table class="table">
            <tr>
                <td colspan="1">
                    <div class="card">
                        <div class="card-body p-3 text-center">
                            <div class="text-right text-green">
                                &nbsp;
                            </div>
                            <div class="h1 m-0">{{ now()->toTimeString() }}</div>
                            <div class="text-muted mb-4">Server Time (GMT)</div>
                        </div>
                    </div>
                </td>
                <td colspan="1">
                    <div class="card">
                        <div class="card-body p-3 text-center">
                            <div class="text-right text-green">
                                &nbsp;
                            </div>
                            <div class="h1 m-0">{{ currency($clients->count(),true,0,true) }}</div>
                            <div class="text-muted mb-4">Clients</div>
                        </div>
                    </div>
                </td>
                <td colspan="2">
                    <div class="card">
                        <div class="card-body p-3 text-center">
                            <div class="text-right">
                                &nbsp;&nbsp;USD
                            </div>
                            <div class="h1 m-0">{{ currency($totalFund,true,2) }}</div>
                            <div class="text-muted mb-4">Total Club Fund</div>
                        </div>
                    </div>
                </td>
                @foreach($periods as $x => $period)
                    <td colspan="2">
                        <div class="card">
                            <div class="card-body p-3 text-center">
                                <div class="text-right">
                                    &nbsp;&nbsp;USD
                                </div>
                                @php
                                    $profit  =  \App\Transaction::query()->whereBetween('created_at',[$period->start,$period->end])->profit();
                                @endphp
                                <div class="h1 m-0">{{ currency($profit,true,2,!true) }}</div>
                                <div class="text-muted mb-4"> Profit ({{ $period->name }})</div>
                            </div>
                        </div>
                    </td>
            @endforeach
                <td colspan="2">
                    <div class="card">
                        <div class="card-body p-3 text-center">
                            <div class="text-right">
                                &nbsp;&nbsp;USD
                            </div>

                            <div class="h1 m-0">{{ currency($deposits,true,2,!true) }}</div>
                            <div class="text-muted mb-4"> Total Deposits </div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        <div class="card mx-3">
            <table class="table table-striped">
                <thead>
                <tr>
                    <td><b>Ticket</b></td>
                    <td><b>Type</b></td>
                    <td><b>Item</b></td>
                    <td><b>Amount</b></td>
                    <td><b>Date (GMT)</b></td>
                </tr>
                </thead>
                <tbody>
                @foreach(\App\Transaction::query()->orderByDesc('closed_at')->paginate(20) as $interest)
                    <tr>
                        <td><b>{{  $interest->ticket }}                    </b></td>
                        <td><b>{{ $interest->type }}</b></td>
                        <td><b>{{ $interest->item }}</b></td>
                        <td><b>{{ currency( $interest->profit,true,2) }}</b></td>
                        <td><b>{{ $interest->closed_at }}</b></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
