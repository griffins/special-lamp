@extends('layouts.main')
@section('styles')
    <style>
        @page {
            size: landscape !important;
        }
    </style>
@endsection
@section('title')
    Reports
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            {{ucfirst( user()->role)}} Reports
        </div>
        <div class="card-body">
            <div class="col-12 p-0 mt-3">
                @foreach($reports as $report)
                    @if(!in_array($report->slug,['account_statement','account_ftp_statement']))
                        <a class="btn btn-outline-primary mr-1 mt-1"
                           href="{{ route('report',['report' => $report->slug]) }}">{{$report->title}}</a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection