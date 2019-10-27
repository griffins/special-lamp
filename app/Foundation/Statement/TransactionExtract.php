<?php


namespace App\Foundation\Statement;


class TransactionExtract
{
    /**
     * @var $dom DomExtract
     */
    public $dom;
    public $ticket;
    public $type;
    public $size;
    public $item;
    public $opened_at;
    public $closed_at;
    public $commission;
    public $swap;
    public $profit;
}