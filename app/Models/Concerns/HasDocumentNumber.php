<?php

namespace App\Models\Concerns;

use App\Support\DocumentNumber;

trait HasDocumentNumber
{
    public static function bootHasDocumentNumber(): void
    {
        static::creating(function (self $model): void {
            if (blank($model->number)) {
                $model->number = DocumentNumber::next($model->documentPrefix());
            }
        });
    }

    abstract public function documentPrefix(): string;
}
