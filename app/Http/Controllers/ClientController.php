<?php

namespace App\Http\Controllers;

use App\Account;
use App\Client;
use App\InvestorTransaction;
use App\Jobs\InvestorComputation;
use App\Notifications\TransactionRequest;
use App\Request;
use App\SupportTicket;
use App\Transaction;
use App\User;
use Carbon\Carbon;
use function request;

class ClientController extends Controller
{
    /**
     * HomeController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:admin,client');
    }

    public function index(Client $client)
    {
//        (new InvestorComputation())->handle();
        $types = ['General', 'Dispute', 'Financial'];
        $periods = (object)[
            (object)[
                'name' => 'Today',
                'start' => now()->startOfDay(),
                'end' => now()->endOfDay()],
            (object)[
                'name' => 'Total Profits',
                'start' => Carbon::parse('first day of jan 2019'),
                'end' => now()],
        ];
        return view('client.profile', compact('client', 'periods', 'types'));
    }

    public function profit()
    {
        Client::updateBalances2(Account::query()->findOrFail(request('account_id')), request('value_type'), request('amount'), Carbon::parse(request('date')));
        return back()->withMessage('Successful');
    }

    public function openTicket(Client $client)
    {
        $types = ['General', 'Dispute', 'Financial'];

        request()->validate([
            'type' => 'required|in:' . implode(',', $types),
            'subject' => 'required',
            'narration' => 'required',
        ]);
        $ticket = new SupportTicket(request()->only('type', 'subject', 'narration'));
        $client->tickets()->save($ticket);
        session()->put("message", "Ticket Opened");
        return redirect(route('client', compact('client')));
    }

    public function transaction(Client $client)
    {
        if (user()->role == 'admin') {
            $account = Account::query()->findOrFail(cache('default_wallet'));
            $time = request('date');
            $ticket = md5($client->email . $time);
            $client->transactions()->save(new Transaction(['type' => request('operation'), 'account_id' => $account->id, 'amount' => request('amount'), 'item' => 'BTC', 'created_at' => $time, 'ticket' => $ticket]));
        } else {
            $time = now();
            $ticket = request('transaction_id');
            $req = new Request(['operation' => request('operation'), 'wallet' => request('wallet'), 'amount' => request('amount'), 'status' => 'pending', 'item' => 'BTC', 'created_at' => $time, 'transaction_id' => $ticket]);
            $client->requests()->save($req);
            foreach (User::query()->get() as $user) {
                $user->notify(new TransactionRequest($req));
            }
        }
        return redirect(route('client', compact('client')));
    }

    public function markTransaction(Transaction $transaction)
    {
        if (!InvestorTransaction::query()->where('transaction_id', $transaction->id)->exists()) {
            $transaction = new InvestorTransaction([
                'transaction_id' => $transaction->id,
                'investor_id' => request('investor'),
                'amount' => $transaction->profit,
                'narration' => $transaction->item,
                'type' => $transaction->type,
                'date' => $transaction->closed_at,
            ]);
            $transaction->save();
        }
        $this->dispatch(new InvestorComputation());
        return 200;
    }

}

