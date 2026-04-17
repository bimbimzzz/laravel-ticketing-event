<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'quantity',
        'total_price',
        'status_payment',
        'event_date',
        'payment_url',
        'promo_code',
        'discount_amount',
        'cancel_reason',
        'cancelled_at',
        'refund_note',
        'refund_proof',
        'refunded_at',
    ];

    protected $casts = [
        'cancelled_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
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
