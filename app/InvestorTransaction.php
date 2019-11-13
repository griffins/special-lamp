<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class InvestorTransaction extends Model
{
    protected $guarded = ['id'];

    public function scopeProfit($query)
    {
        return $query->selectRaw("sum( CASE WHEN type = 'withdraw' THEN 0 - amount ELSE amount END ) as aggregate")->value('aggregate') ?: 0;
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }


    public function scopeDeposits($query)
    {
        return $query->where('type', 'deposit')->selectRaw("sum( amount ) as aggregate")->value('aggregate') ?: 0;
    }

    public function scopeWithdrawals($query)
    {
        return $query->where('type', 'withdraw')->selectRaw("sum( amount ) as aggregate")->value('aggregate') ?: 0;
    }

    public function scopeBalanceAt(Builder $query, $date)
    {
        return ($query->where('date', '<=', $date)
            ->selectRaw("sum( CASE WHEN type = 'withdraw' THEN 0 - amount ELSE amount END ) as aggregate")->value('aggregate')) ?: 0;
    }

    public function scopeDepositAt(Builder $query, $date)
    {
        return ($query->where('date', '<=', $date)
            ->where('type', 'deposit')
            ->selectRaw("sum( amount ) as aggregate")->value('aggregate')) ?: 0;
    }

    public function scopeBalance(Builder $query)
    {
        return $query->balanceAt(now());
    }
}
