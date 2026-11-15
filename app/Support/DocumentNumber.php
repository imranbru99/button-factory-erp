<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DocumentNumber
{
    public static function next(string $prefix): string
    {
        return DB::transaction(function () use ($prefix) {
            $day = now()->format('ymd');
            $key = $prefix.'-'.$day;

            $row = DB::table('document_counters')->where('key', $key)->lockForUpdate()->first();
            $seq = $row ? ((int) $row->value) + 1 : 1;

            if ($row) {
                DB::table('document_counters')->where('key', $key)->update(['value' => $seq]);
            } else {
                DB::table('document_counters')->insert(['key' => $key, 'value' => $seq]);
            }

            return $key.'-'.str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
        });
    }

    public static function code(string $prefix): string
    {
        return $prefix.'-'.strtoupper(Str::random(6));
    }
}
