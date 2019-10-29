<?php

namespace App\Http\Controllers;

use App\Client;
use App\InvestorTransaction;
use App\Transaction;
use App\User;
use Carbon\Carbon;

class HomeController extends Controller
{

    /**
     * HomeController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:admin,client');
    }

    public function index()
    {
        if (user()->role == 'admin') {
            $periods = (object)[
                (object)[
                    'name' => 'Today',
                    'start' => now()->startOfDay(),
                    'end' => now()->endOfDay()],
                (object)[
                    'name' => 'Total Profits',
                    'start' => Carbon::parse('first day of august 2019'),
                    'end' => now()->endOfYear()],
            ];
            $clients = Client::query();
            $totalFund = InvestorTransaction::query()->balance();
            return view('home', compact('clients', 'totalFund','periods'));
        } else {
            return redirect(route('client', ['client' => user()]));
        }
    }

    public function clientDashboard(Client $client)
    {
        $periods = (object)[
            (object)[
                'name' => 'Today',
                'start' => now()->startOfDay(),
                'end' => now()->endOfDay()],
            (object)[
                'name' => 'Total Profits',
                'start' => Carbon::parse('first day of august 2019'),
                'end' => now()->endOfYear()],
        ];

        $accounts = $client->accounts();
        $referrals = $client->referrals();
        $currencies = [];
        $r_currencies = [];
        foreach ($client->accounts()->distinct()->get('currency') as $cur) {
            $currency = [
                'code' => $cur->currency,
                'accounts' => $client->accounts()->where('currency', $cur->currency),
                'transactions' => Transaction::query()
                    ->whereIn('account_id', $client->accounts()->where('currency', $cur->currency)->pluck('id'))
            ];
            $currencies[$cur->currency] = $currency;
        }
        foreach ((clone $referrals)->distinct()->get(['currency']) as $cur) {
            $currency = [
                'code' => $cur->currency,
                'accounts' => (clone $referrals)->where('currency', $cur->currency),
                'transactions' => Transaction::query()
                    ->whereIn('account_id', (clone $referrals)->where('currency', $cur->currency)->pluck('accounts.id'))
            ];
            $r_currencies[$cur->currency] = $currency;
        }
        return view('client.dashboard', compact('accounts', 'referrals', 'periods', 'client', 'currencies', 'r_currencies'));
    }
}
