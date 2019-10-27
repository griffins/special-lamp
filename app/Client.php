<?php

namespace App;

use App\Foundation\Statement\EmailExtract;
use App\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Client extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    public $role = 'client';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'notes', 'profits', 'wallet', 'status', 'account_id', 'client_deposit_total'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getPhotoAttribute()
    {
        if ($this->photos()->count() > 0) {
            return $this->photos()->first()->link;
        }
        return Photo::avatar();
    }

    public function investorTransactions()
    {
        return $this->hasMany(InvestorTransaction::class);
    }


    public function getAccountBalanceAttribute()
    {
        return $this->investorTransactions()->balance();
    }

    public function photos()
    {
        return $this->morphMany(Photo::class, "profile");
    }

    public function scopeByAdminRole(Builder $query)
    {
        return user()->club == '*' ? $query : $query->where('club', 'regexp', sprintf("^(P)"));
    }

    public function getBalanceAttribute()
    {
        return $this->balanceAt(now());
    }

    public function transactions()
    {
        return $this->hasMany(InvestorTransaction::class,'investor_id');
    }

    public function balanceAt($date)
    {
        return ($this->transactions()
            ->where('date', '<=', $date)
            ->selectRaw("sum( CASE WHEN type = 'withdrawal' THEN 0 - amount ELSE amount END ) as aggregate")->value('aggregate')) ?: 0;
    }

    public function tickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function account()
    {
        return $this->belongsTo(Inve::class);
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    /**
     * Send the password reset notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
}
