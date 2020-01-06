<?php

namespace App\Http\Controllers;

use App\Account;
use App\Client;
use App\Foundation\Statement\DomExtract;
use App\Foundation\Statement\TransactionExtract;
use App\InvestorTransaction;
use App\Jobs\InvestorComputation;
use App\SupportTicket;
use App\Transaction;
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
        $extract = new DomExtract();
        $extract->account = request('account_id');
        $transaction = new TransactionExtract();
        $transaction->dom = $extract;
        $transaction->ticket = request('ticket');
        $transaction->type = request('type');
        $transaction->opened_at = request('opened_at');
        $transaction->size = request('size');
        $transaction->item = "xauusd";
        $transaction->commission = request('commission');
        $transaction->swap = request('swap');
        $transaction->closed_at = request('closed_at');
        $transaction->profit = request('amount');
        $extract->transactions[] = $transaction;
        Account::importFromExtract($extract);
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
            $time = request('date');
            $client->investorTransactions()->save(new InvestorTransaction(['type' => request('operation'), 'amount' => request('amount'), 'account_id' => request('account_id'), 'narration' => 'Client ' . request('operation'), 'date' => $time]));
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

