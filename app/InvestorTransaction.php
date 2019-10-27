<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvestorTransaction extends Model
{
    protected $guarded = ['id'];

    public function scopeProfit($query)
    {
        return $query->selectRaw("sum( CASE WHEN type = 'withdrawal' THEN 0 - amount ELSE amount END ) as aggregate")->value('aggregate') ?: 0;
    }
}
