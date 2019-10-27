@extends('layouts.main')
@section('title')
    Support Center
    @if(request('trashed') != 'true')
        <a data-turbolinks="false" href="{{route('support.resolution', ['action' => 'listing','trashed' => "true"])}}"
           class="btn btn-outline-light float-right">Closed Tickets</a>
    @else
        <a data-turbolinks="false" href="{{route('support.resolution', ['action' => 'listing'])}}"
           class="btn btn-primary float-right">Open Tickets</a>
    @endif
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"> @if(request('trashed') != 'true') Open Tickets @else Closed Tickets @endif</h3>
            <div class="card-options">
            </div>
        </div>
        <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap">
                <thead>
                <tr>
                    <th class="w-1">No.</th>
                    <th>Type</th>
                    <th>Subject</th>
                    <th>Client</th>
                    <th>Last Updated</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($tickets as $ticket)
                    <tr>
                        <td><span class="text-muted">{{ $ticket->id }}</span></td>
                        <td>{{ $ticket->type }}</td>
                        <td class="wrap">
                            <a href="{{ route('support.resolution',['action'=>'view','ticket' => $ticket]) }}">{{ $ticket->subject }}</a>
                        </td>
                        <td>
                            <a href="{{ route("client",['client' => $ticket->client]) }}">{{ $ticket->client->name }}</a>
                        </td>
                        <td>
                            {{ $ticket->updated_at->format('jS M Y') }}
                        </td>
                        <td>
                            <span class="status-icon @if($ticket->status == 'pending') bg-success @else bg-secondary @endif"></span> {{  ucfirst( $ticket->status) }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
