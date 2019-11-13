<?php

namespace App\Http\Controllers\Reports;

use App\Account;
use App\Client;
use App\Server;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

trait FinancialReport
{


    private function accountStatement(Request $request, $report)
    {
        $account = Account::query()->find($request->account);
        $dates = [];
        for ($i = 0; $i < 12; $i++) {
            $dates [] = now()->subMonth($i)->firstOfMonth(Carbon::SATURDAY);
        }
        $load = ['report' => $report, 'from' => $request->from, 'dates' => $dates];
        if ($account) {
            $load['account'] = $account;
        }
        if (request()->has('from') && request()->has('to')) {
            list($from, $to) = $this->parseDates();
            if ($account) {
                $query = $account->transactions();
            } else {
                $query = Transaction::query();
            }
            $query->whereBetween('closed_at', [$from, $to])
                ->orderBy('created_at');

            $load['transactions'] = $query;
            $load['to'] = $to;
            $load['from'] = $from;
        }
        return view('reports.finance.statement', $load);
    }

    private function clientDeposits(Request $request, $report)
    {

        $load = ['report' => $report];

        $query = Client::query();
        $load['query'] = $query;
        return view('reports.finance.deposits', $load);
    }

}
