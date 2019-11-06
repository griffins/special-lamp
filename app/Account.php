<?php

namespace App;

use App\Foundation\Statement\DomExtract;
use App\Jobs\InvestorComputation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $guarded = ['id'];

    protected $casts = ['cc' => 'array'];

    public static function importFromExtract(DomExtract $extract)
    {
        $static = static::query()->where('account', $extract->account)->first();
        if ($static) {
            $static->import($extract);
            dispatch(new InvestorComputation($static->id));
        }
    }

    public function pendingTransactions()
    {
        return $this->hasMany(Transaction::class)->whereNotIn('type', ['buy', 'sell'])->doesntHave('investorTransaction');
    }

    public function balanceAt($date)
    {
        return ($this->transactions()
            ->where('closed_at', '<=', $date)
            ->selectRaw("sum( CASE WHEN type = 'withdrawal' THEN 0 - profit + commission + swap ELSE profit + commission + swap END ) as aggregate")->value('aggregate')) ?: 0;
    }

    public function investorTransactions()
    {
        return $this->hasMany(InvestorTransaction::class);
    }

    public function investors()
    {
        return $this->hasMany(Investor::class);
    }

    public function scopeByAdminRole(Builder $query)
    {
        return user()->club == '*' ? $query : $query->whereIn('server_id', Server::query()->where('name', 'regexp', sprintf("^(P)"))->pluck('id'));
    }

    public function import(DomExtract $extract)
    {
        foreach ($extract->transactions as $transaction) {
            $transaction->account_id = $extract->account->id;
            Transaction::fromExtract($transaction);
        }
    }

    public function chartOf($type)
    {
        switch ($type) {
            case  'weekly':
                return $this->weeklyChart();
            case  'monthly':
                return $this->monthlyChart();
            default:
                return $this->dailyChart();
        }
    }

    private function dailyChart()
    {
        $response = [
            'id' => $this->account,
            'labels' => [], 'series' => ['data']];
        foreach (range(6, 0) as $x) {
            $response['labels'][] = now()->subDays($x)->format('D');
            $response['series'][] = $this->transactions()
                ->where('closed_at', '<=',
                    now()->subDays($x)->endOfDay()
                )
                ->profit();
        }
        return $response;
    }

    private function weeklyChart()
    {
        $response = [
            'id' => $this->account,
            'labels' => [], 'series' => ['data']];
        foreach (range(6, 0) as $x) {
            $response['labels'][] = "Week " . now()->subWeeks($x)->format('W');
            $response['series'][] = $this->transactions()
                ->where('closed_at', '<=',
                    now()->subWeeks($x)->endOfWeek())
                ->profit();
        }
        return $response;
    }

    private function monthlyChart()
    {
        $response = [
            'id' => $this->account,
            'labels' => [], 'series' => ['data']];
        foreach (range(6, 0) as $x) {
            $response['labels'][] = now()->subMonths($x)->format('M');
            $response['series'][] = $this->transactions()
                ->where('closed_at', '<=',
                    now()->subMonths($x)->endOfMonth()
                )
                ->profit();
        }
        return $response;
    }

    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
