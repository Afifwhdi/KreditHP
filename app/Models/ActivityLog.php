<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ActivityLog extends Model
{
    protected $fillable = ['user_id', 'action', 'model', 'data_before', 'data_after'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
