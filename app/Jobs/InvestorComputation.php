<?php

namespace App\Jobs;

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

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $investors = Client::query()->get();
        \DB::beginTransaction();
        InvestorTransaction::query()->where('type', 'profit')->delete();
        foreach (Transaction::query()->whereIn('type', ['sell', 'buy'])->whereNotNull('closed_at')->orderBy('closed_at', 'asc')->cursor(0) as $transaction) {
            foreach ($investors as $investor) {
                if (!$investor->investorTransactions()->where('transaction_id', $transaction->id)->exists()) {
                    $balance = $investor->balanceAt($transaction->opened_at);
                    $bal = InvestorTransaction::query()->balanceAt($transaction->opened_at);
                    if ($bal !== 0 && $balance !== 0) {
                        $profit2 = ((100 - $investor->commission) / 100) * ($transaction->profit + $transaction->swap + $transaction->commission) * ($balance / $bal);
                        $profit = ($investor->commission / 100) * ($transaction->profit + $transaction->swap + $transaction->commission) * ($balance / $bal);
                        if ($profit != 0) {
                            $t = new InvestorTransaction([
                                'transaction_id' => $transaction->id,
                                'investor_id' => $investor->id,
                                'amount' => $profit,
                                'narration' => 'P/L-' . $transaction->ticket,
                                'type' => 'profit',
                                'date' => $transaction->closed_at,
                            ]);
                            $t->save();
                        }
                        if ($profit2 > 0) {
                            $t = new InvestorTransaction([
                                'transaction_id' => $transaction->id,
                                'investor_id' => Client::query()->first()->id,
                                'amount' => $profit2,
                                'narration' => 'P/L-' . $transaction->ticket,
                                'type' => 'profit',
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
