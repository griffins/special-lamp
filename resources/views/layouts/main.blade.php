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
                                            <a href="{{ route('support',['section' => 'accounts']) }}"
                                               class="dropdown-item @if(request('section') =='accounts') active @endif">
                                                Accounts
                                            </a>
                                            <a href="{{route('support',['section' => 'clients'])}}"
                                               class="dropdown-item @if(request('section') =='clients') active @endif">
                                                Clients
                                            </a>
                                            <a href="{{route('support',['section' => 'requests'])}}"
                                               class="dropdown-item @if(request('section') =='requests') active @endif">
                                                Registration Requests
                                            </a>
                                            <a href="#"
                                               data-toggle="modal" data-target="#profit"
                                               class="dropdown-item">
                                                Profits
                                            </a>
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
        </div>
        <div class="modal fade" id="profit" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalCenterTitle"
             aria-hidden="true">
            <div class="modal-dialog  modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form method="POST" action="{{ route('profit') }}">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTitle">Profit Transaction</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <label class="col-form-label">Account:</label>
                                <select class="form-control" name="account_id">
                                    @foreach(\App\Account::query()->get() as $account)
                                        <option value="{{$account->account}}">{{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <label class="col-form-label">Type:</label>
                                        <select class="form-control" name="type">
                                            <option value="sell">Sell</option>
                                            <option value="buy">Buy</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="col-form-label">Ticket:</label>
                                        <input type="text" name="ticket" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-4">
                                            <label class="col-form-label">Size:</label>
                                            <input type="number" name="size" step="0.00000001" class="form-control">
                                        </div>
                                        <div class="col-4">
                                            <label class="col-form-label">Commission:</label>
                                            <input type="number" name="commission" step="0.00000001"
                                                   class="form-control">
                                        </div>
                                        <div class="col-4">
                                            <label class="col-form-label">Swap:</label>
                                            <input type="number" name="swap" step="0.00000001" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-4">
                                            <label class="form-label">Profit/Loss:</label>
                                            <input type="number" name="amount" step="0.00000001" class="form-control">
                                        </div>
                                        <div class="col-4">
                                            {{ date_picker('Opened At','opened_at', now()->toDateTimeString()) }}
                                        </div>
                                        <div class="col-4">
                                            {{ date_picker('Closed At','closed_at', now()->toDateTimeString()) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                    </form>
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

