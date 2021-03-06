@extends('layouts.main')
@section('title')
    Account / {{ $client->name }}
    @if(user()->role=='admin')
        @php
            $recipients = base64_encode(json_encode([$client->email]));
            session()->put('url.intended', URL::full());
        @endphp
        <a data-turbolinks="false" href="{{route('mailbox', compact('recipients'))}}"
           class="mr-2 btn btn-primary float-right">Send Email</a>
    @endif
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-4">
            <div class="card card-profile">
                <div class="card-header"
                     style="background-image: url(https://preview.tabler.io/demo/photos/eberhard-grossgasteiger-311213-500.jpg);"></div>
                <div class="card-body text-center">
                    <span class="avatar avatar-xxl mr-5 card-profile-img"
                          style="background-image: url({{$client->photo}})"></span>
                    <h3 class="mb-3">{{ $client->name }} </h3>
                    <p class="mb-4">
                        {{$client->email}}
                        <br>
                        {{ $client->phone }}
                    </p>
                    <i
                        class="flag flag-{{ strtolower($client->country_code) }}"></i>
                </div>
            </div>
            @if(user()->role=='admin')
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Notes</h3>
                        <div class="card-options">
                            <a class="icon"
                               href="{{ route('support',['action' => 'edit','section' => 'clients','client' => $client]) }}">
                                <i class="fe fe-edit"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <p>{{ $client->notes?: "N/A" }}</p>
                    </div>
                </div>
                <div class="card" id="ticket">
                    <div class="card-header">
                        <h3 class="card-title">Create Ticket</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('client.ticket',compact('client')) }}#ticket">
                            @csrf
                            <div class="form-group">
                                <label class="form-label">Ticket Type</label>
                                <select class="form-control custom-select" name="type">
                                    <option value="">Select Ticket Type</option>
                                    @foreach($types as $type)
                                        <option @if(old('type') == $type) selected @endif>{{ $type }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('type'))
                                    <span class="invalid-feedback d-block" role="alert">
                                            <strong> {{ $errors->first('type') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="form-label">Subject</label>
                                <input type="text" class="form-control" name="subject"
                                       placeholder="Ticket Subject.."
                                       value="{{ old('subject') }}">
                                @if ($errors->has('subject'))
                                    <span class="invalid-feedback d-block" role="alert">
                                            <strong> {{ $errors->first('subject') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="narration" rows="6"
                                          placeholder="Narration..">{{ old('narration') }}</textarea>
                                @if ($errors->has('narration'))
                                    <span class="invalid-feedback d-block" role="alert">
                                            <strong> {{ $errors->first('narration') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-footer">
                                <button class="btn btn-primary btn-block">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-lg-8">
            @include('client.account')
        </div>
    </div>
    @if(user()->role =='admin')
        <div class="modal fade" id="transaction" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalCenterTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form method="POST" action="{{ route('transaction',compact('client')) }}">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTitle">Transaction</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <input type="hidden" name="operation" value="add">
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <label class="col-form-label">Amount:</label>
                                <input type="number" name="amount" step="0.00000001" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">Account:</label>
                                <select class="form-control" name="account_id">
                                    @foreach(\App\Account::all() as $account)
                                        <option value="{{ $account->id }}">{{$account->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                {{ date_picker('Date','date', now()->toDateTimeString()) }}
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
    @endif
@endsection
@section('scripts')
    <script>
        $('[data-toggle="card-collapse"]').on('click', function (e) {
            const DIV_CARD = 'div.card';

            let $card = $(this).closest(DIV_CARD);

            $card.toggleClass('card-collapsed');

            e.preventDefault();
            return false;
        });

        function performTransaction() {
            $('#transaction').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var operation = button.data('type');
                var modal = $(this);
                if (operation === 'withdraw') {
                    modal.find('.modal-title').text('Client Withdrawal');
                } else {
                    modal.find('.modal-title').text('Client Deposit');

                }
                modal.find('.modal-content input[name=operation]').val(operation);
            })
        }

        performTransaction()
    </script>
@append
