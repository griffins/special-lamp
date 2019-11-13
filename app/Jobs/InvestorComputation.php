<?php

namespace App\Jobs;

use App\Account;
use App\Client;
use App\InvestorTransaction;
use App\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class InvestorComputation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $account;

    public function __construct($id)
    {
        $this->account = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $investors = Client::query()->get();
        $account = Account::query()->find($this->account);
        \DB::beginTransaction();
        $account->investorTransactions()->whereIn('type', ['profit', 'buy', 'sell'])->delete();
        foreach ($account->transactions()->whereIn('type', ['sell', 'buy'])->whereNotNull('closed_at')->orderBy('closed_at', 'asc')->cursor(0) as $transaction) {
            foreach ($investors as $investor) {
                if (!$investor->investorTransactions()->where('transaction_id', $transaction->id)->exists()) {
                    $balance = $investor->depositAt($transaction->opened_at);
                    $ratio = $investor->depositAt($transaction->opened_at) / 1000;
                    $amount = ($transaction->profit + $transaction->swap + $transaction->commission) * 0.7;
                    if ($balance !== 0) {
                        $profit2 = ((100 - $investor->commission) / 100) * $amount * $ratio;
                        $profit = ($investor->commission / 100) * $amount * $ratio;
                        if ($profit != 0) {
                            $t = new InvestorTransaction([
                                'transaction_id' => $transaction->id,
                                'investor_id' => $investor->id,
                                'amount' => $profit,
                                'narration' => $transaction->type,
                                'type' => $transaction->item,
                                'date' => $transaction->closed_at,
                            ]);
                            $t->save();
                        }
                        if ($profit2 != 0) {
                            $t = new InvestorTransaction([
                                'transaction_id' => $transaction->id,
                                'investor_id' => Client::query()->first()->id,
                                'amount' => $profit2,
                                'narration' => $transaction->type,
                                'type' => $transaction->item,
                                'date' => $transaction->closed_at,
                            ]);
                            $t->save();
                        }
                    }
                }
            }
        }
        \DB::commit();
    }
}
