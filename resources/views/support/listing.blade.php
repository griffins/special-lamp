@extends('layouts.main')
@section('title')
    Support Center
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Your Tickets</h3>
            <div class="card-options">
                <a href="{{ route('support.ticket',['action' => 'open']) }}" class="btn btn-primary btn-sm">New
                    Ticket</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap">
                <thead>
                <tr>
                    <th class="w-1">No.</th>
                    <th>Type</th>
                    <th>Subject</th>
                    <th>Last Updated</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach(auth()->user()->tickets as $ticket)
                    <tr>
                        <td><span class="text-muted">{{ $ticket->id }}</span></td>
                        <td>{{ $ticket->type }}</td>
                        <td class="wrap">
                            <a href="{{ route('support.ticket',['action'=>'view','ticket' => $ticket]) }}">{{ $ticket->subject }}</a>
                        </td>
                        <td>
                            {{ $ticket->updated_at->format('jS M Y') }}
                        </td>
                        <td>
                            <span class="status-icon @if($ticket->status =='pending') bg-success @else bg-secondary @endif"></span> {{  ucfirst( $ticket->status) }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
