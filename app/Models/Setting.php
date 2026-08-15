<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'key',
    'value',
    'group',
    'type',
])]
class Setting extends Model
{
    public function decodedValue(): mixed
    {
        if ($this->type === 'json' && $this->value !== null) {
            $decoded = json_decode($this->value, true);

            return json_last_error() === JSON_ERROR_NONE ? $decoded : $this->value;
        }

        return $this->value;
    }
}
