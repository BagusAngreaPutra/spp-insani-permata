<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Convert formatted Rupiah request values back to plain numeric values.
     */
    protected function normalizeCurrencyFields(Request $request, array $fields): void
    {
        $normalized = [];

        foreach ($fields as $field) {
            if (!$request->exists($field)) {
                continue;
            }

            $normalized[$field] = $this->normalizeCurrencyValue($request->input($field));
        }

        if ($normalized !== []) {
            $request->merge($normalized);
        }
    }

    private function normalizeCurrencyValue(mixed $value): mixed
    {
        if (is_array($value)) {
            return array_map(fn ($item) => $this->normalizeCurrencyValue($item), $value);
        }

        if ($value === null || $value === '') {
            return $value;
        }

        $text = trim((string) $value);
        if (is_numeric($text)) {
            return $text;
        }

        $digits = preg_replace('/[^0-9]/', '', $text);

        return $digits === '' ? $value : $digits;
    }
}
