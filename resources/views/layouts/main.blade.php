@extends('layouts.app')

@section('body')
    <div class="page-main">
        <div class="header py-4">
            <div class="container">
                <div class="d-flex mx-4">
                    <a class="header-brand text-white" href="{{ url('/') }}">
                        <img src="{{ asset('images/logo.svg') }}" class="header-brand-img"
                             alt="{{config('app.name')}} logo"> {{ config('app.name') }}
                    </a>
                    <div class="d-flex order-lg-2 ml-auto">
                        @if(!(user()))
                            <div class="nav-item d-none d-md-flex">
                                <a href="{{ url('login') }}" class="btn btn-sm btn-outline-primary">Sign In</a>
                            </div>
                            <div class="nav-item d-none d-md-flex p-0">
                                <a href="{{ url('register') }}" class="btn btn-sm btn-primary">Register</a>
                            </div>
                        @else
                            <div class="dropdown">
                                <a href="#" class="nav-link pr-0 text-white leading-none" data-toggle="dropdown">
                                    <span class="avatar"
                                          style="background-image: url({{  user()->photo }})"></span>
                                    <span class="ml-2 d-none d-lg-block">
                      <span class="text-white">{{ user()->name }}</span>
                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                    <a class="dropdown-item" href="{{  url('profile')  }}">
                                        <i class="dropdown-icon fe fe-user"></i> Profile
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <form id="logout-form"
                                          action="{{  route('logout') }}"
                                          method="POST"
                                          style="display: none;">{{ csrf_field() }}</form>

                                    <a class="dropdown-item" href="#"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="dropdown-icon fe fe-log-out"></i> Sign out
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                    <a href="#" class="header-toggler d-lg-none ml-3 ml-lg-0" data-toggle="collapse"
                       data-target="#headerMenuCollapse">
                        <span class="header-toggler-icon"></span>
                    </a>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="header collapse d-lg-flex p-0" id="headerMenuCollapse">
                <div class="row align-items-center mx-4">
                    @if(route_matches('dashboard'))
                        <div class="col-lg-3 ml-auto">
                            <form class="input-icon my-3 my-lg-0">
                                <input type="search" class="form-control header-search" placeholder="Search&hellip;"
                                       tabindex="1">
                                <div class="input-icon-addon">
                                    <i class="fe fe-search"></i>
                                </div>
                            </form>
                        </div>
                    @endif

                    <div class="col-lg order-lg-first">
                        <ul class="nav nav-tabs border-0 flex-column flex-lg-row">
                            <li class="nav-item">
                                <a class="nav-link text-white  @if(route_matches('/')) active @endif"
                                   href="{{ url('/') }}"><i class="fe fe-home"></i>Dashboard</a>
                            </li>
                            @if(user())
                                @if(user()->role == 'admin')
                                    <li class="nav-item">
                                        <a class="nav-link text-white @if(route_matches('support')) active @endif"
                                           href="{{ route('support') }}" data-toggle="dropdown"> <i
                                                class="fe fe-settings"></i>
                                            Administration
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-arrow">
                                            @if(user()->id != 4)
                                                <a href="{{ route('support',['section' => 'users']) }}"
                                                   class="dropdown-item @if(request('section') =='users') active @endif">
                                                    Administrators
                                                </a>
                                            @endif
                                            <a href="{{route('support',['section' => 'clients'])}}"
                                               class="dropdown-item @if(request('section') =='clients') active @endif">
                                                Clients
                                            </a>
                                            <a href="#"
                                               data-toggle="modal" data-target="#mark-transactions"
                                               title="Transaction Marker"
                                               class="dropdown-item">Mark Transactions</a>
                                        </div>
                                    </li>
                                    @if(user()->id != 4)
                                        <li class="nav-item">
                                            <a data-turbolinks="false"
                                               class="nav-link text-white @if(route_matches('mailbox')) active @endif"
                                               href="{{ route('mailbox') }}"><i class="fe fe-mail"></i>Mail Box</a>
                                        </li>
                                    @endif
                                    <li class="nav-item">
                                        <a class="nav-link text-white @if(route_matches('reports')) active @endif"
                                           href="{{ route('report') }}"><i class="fe fe-file"></i> Reports</a>
                                    </li>
                                @endif
                                @if(user()->id != 4)
                                    {{--                                    <li class="nav-item">--}}
                                    {{--                                        <a class="nav-link text-white @if(route_matches('support.resolution')) active @endif"--}}
                                    {{--                                           href="{{ route('support.resolution') }}"> <i class="fe fe-life-buoy"></i>Help--}}
                                    {{--                                            Desk</a>--}}
                                    {{--                                    </li>--}}
                                @endif
                            @else
                                {{--                                <li class="nav-item">--}}
                                {{--                                    <a class="nav-link text-white @if(route_matches('support.ticket')) active @endif"--}}
                                {{--                                       href="{{ route('support.ticket') }}"> <i class="fe fe-life-buoy"></i>Help--}}
                                {{--                                        Desk</a>--}}
                                {{--                                </li>--}}
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <div class="my-3 my-md-5 mx-4">
                <div class="page-header mx-2">
                    <h1 class="page-title w-100 text-white">
                        @yield('title')
                    </h1>
                </div>
                @if(session()->has('success'))
                    @php $message = isset($message) ? $message :session()->pull('success') @endphp
                @endif
                @if(session()->has('failure'))
                    @php $error = isset($error)? $error : session()->pull('failure') @endphp
                @endif
                @if(isset($message))
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-success" data-dismiss="alert" role="alert">
                                <button type="button" class="close" data-dismiss="alert"></button>
                                {{ __($message) }}
                            </div>
                        </div>
                    </div>
                @elseif(isset($error))
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-danger" data-dismiss="alert" role="alert">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                {{ __($error) }}
                            </div>
                        </div>
                    </div>
                @endif
                @yield('content')
            </div>
            <div class="modal fade" id="mark-transactions" tabindex="-1" role="dialog"
                 aria-labelledby="exampleModalCenterTitle"
                 aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Unmarked Transactions</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <td>
                                        <b> Ticket </b>
                                    </td>
                                    <td>
                                        <b> Description</b>
                                    </td>
                                    <td>
                                        <b> Amount</b>
                                    </td>
                                    <td>
                                        <b> Date</b>
                                    </td>
                                    <td>
                                        <b>
                                            Actions
                                        </b>
                                    </td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach(\App\Transaction::query()->pendingTransactions()->orderBy('closed_at')->get() as $transaction)
                                    <tr>
                                        <td>
                                            {{$transaction->ticket}}
                                        </td>
                                        <td>
                                            {{ucfirst(  $transaction->type) }}  {{$transaction->item}}
                                        </td>
                                        <td>
                                            {{ currency( $transaction->profit) }}
                                        </td>
                                        <td>
                                            {{$transaction->closed_at->toDateTimeString()}}
                                        </td>
                                        <td>
                                            <div class="item-action dropdown">
                                                <a href="javascript:void(0)" data-toggle="dropdown" class="icon"
                                                   aria-expanded="false"><i
                                                        class="fe fe-more-vertical"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end"
                                                     style="position: absolute; transform: translate3d(15px, 20px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                    @foreach(\App\Client::query()->get() as $investor)
                                                        <a href="#" data-toggle="modal"
                                                           data-target="#mark-transaction-to-investor"
                                                           data-name="{{$investor->name}}"
                                                           data-investor-id="{{$investor->id}}"
                                                           data-url="{{ route('mark',compact('investor','transaction')) }}"
                                                           class="dropdown-item assign"><i
                                                                class="dropdown-icon text-success fe fe-check-circle"
                                                                style=""></i>Mark
                                                            For {{ $investor->name }}</a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="container">
            <footer class="footer text-white">
                <div class="row align-items-center mx-4">
                    <div class="col-12 col-lg-auto mt-3 mt-lg-0 text-center">
                        Copyright © {{ date('Y') }} <a href="." class="text-white">{{ config('app.name') }}</a>.
                        Theme by <a href="https://codecalm.net" class="text-white" target="_blank">codecalm.net</a> All
                        rights reserved.
                    </div>
                </div>
            </footer>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function setupInvestor() {
            $('.assign').on('click', function () {
                var transaction = $(this);
                axios.post(transaction.data('url')).then(function (a, b) {
                    if (a.status === 200) {
                        transaction.closest('tr').remove();
                    }
                });
            });
        }

        $(document).ready(function () {
            setupInvestor();
        });
    </script>
@append

