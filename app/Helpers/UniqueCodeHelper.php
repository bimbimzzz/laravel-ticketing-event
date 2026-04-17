<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UniqueCodeHelper
{
    /**
     * Generate a unique 10-character alphanumeric code.
     *
     * @param string $table
     * @param string $column
     * @return string
     */
    public static function generateUniqueCode($table, $column)
    {
        do {
            $code = Str::upper(Str::random(10)); // Generate 10-character alphanumeric code
            $exists = DB::table($table)->where($column, $code)->exists();
        } while ($exists);

        return $code;
    }
}
