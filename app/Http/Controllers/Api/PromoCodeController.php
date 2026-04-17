<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'event_id' => 'required|exists:events,id',
            'total_price' => 'required|integer|min:1',
        ]);

        $promo = PromoCode::where('code', strtoupper($request->code))
            ->where('event_id', $request->event_id)
            ->first();

        if (!$promo || !$promo->isValid()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kode promo tidak valid atau sudah kadaluarsa.',
            ], 422);
        }

        $discount = $promo->calculateDiscount($request->total_price);

        return response()->json([
            'status' => 'success',
            'data' => [
                'code' => $promo->code,
                'discount_type' => $promo->discount_type,
                'discount_value' => $promo->discount_value,
                'discount' => $discount,
                'final_price' => $request->total_price - $discount,
            ],
        ]);
    }
}
