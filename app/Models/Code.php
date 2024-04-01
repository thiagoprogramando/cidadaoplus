<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Code extends Model {

    use HasFactory;

    protected $table = 'code';

    protected $fillable = [
        'id_user',
        'code',
    ];

    public static function generateCode() {
        $length = 6;
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';
        
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $code;
    }

    protected static function boot() {

        parent::boot();

        static::creating(function ($code) {
            $code->code = self::generateCode();
        });
    }
}
