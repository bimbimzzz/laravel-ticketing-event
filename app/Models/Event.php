<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'event_category_id',
        'name',
        'description',
        'image',
        'start_date',
        'end_date',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }


    public function eventCategory()
    {
        return $this->belongsTo(EventCategory::class);
    }

    public function skus()
    {
        return $this->hasMany(Sku::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function getStatusAttribute(): string
    {
        $now = now()->startOfDay();
        $start = \Carbon\Carbon::parse($this->start_date)->startOfDay();
        $end = \Carbon\Carbon::parse($this->end_date)->endOfDay();

        if ($now->lt($start)) {
            return 'upcoming';
        } elseif ($now->gt($end)) {
            return 'past';
        } else {
            return 'ongoing';
        }
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'upcoming' => 'Akan Datang',
            'ongoing' => 'Berlangsung',
            'past' => 'Selesai',
        };
    }

    public function getStatusVariantAttribute(): string
    {
        return match ($this->status) {
            'upcoming' => 'primary',
            'ongoing' => 'success',
            'past' => 'secondary',
        };
    }
}
