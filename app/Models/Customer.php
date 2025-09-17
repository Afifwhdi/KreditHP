<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'ktp_path',
        'nik',
    ];

    public function credits()
    {
        return $this->hasMany(Credit::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    protected $casts = [
        'nik' => 'string',
    ];
}
