<?php

// app/Models/Setting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    // public static function getValue(string $key, $default = null)
    // {
    //     $value = static::where('key', $key)->value('value');
    //     return $value ?? $default;
    // }

    public static function get($key, $default = null)  // for production
    {
        $record = static::where('key', $key)->first();
        return $record ? $record->value : $default;
    }


    // public static function setValue(string $key, $value)
    // {
    //     return static::updateOrCreate(
    //         ['key' => $key],
    //         ['value' => is_scalar($value) ? $value : json_encode($value)]
    //     );
    // }

    public static function set($key, $value) // for production
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}
