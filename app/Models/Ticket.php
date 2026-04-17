<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku_id',
        'event_id',
        'ticket_code',
        'ticket_date',
        'status',
    ];

    public function sku()
    {
        return $this->belongsTo(Sku::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function orderTickets()
    {
        return $this->hasMany(OrderTicket::class);
    }
}
