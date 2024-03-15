<?php

namespace App\Helpers;

use App\Models\Employee;

class CustomHelper 
{
    public static function removeCurrencyFormat($value)
    {
        $value = str_replace('Rp ', '', $value);
        $value = str_replace('.', '', $value);
        $value = intval($value);
        return $value;
    }

    public static function addCurrencyFormat($value)
    {
        $value = "Rp " . number_format($value, 0, ',', '.');
        return $value;
    }

    public static function getEmployeeNames($data) {
        if (empty($data)) {
        return '';
        }
        $names = [];
        $explodedData = explode(',', $data);
        foreach ($explodedData as $id) {
            if ($id == 'r') {
                $names[] = 'Rekanan';
            } else {
                $names[] = Employee::where('id', $id)->first()->name;
            }
        }
        return implode(', ', $names);
    }
}
