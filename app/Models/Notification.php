<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Installment;
use App\Models\MessageTemplate;

class Notification extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'customer_id',
        'installment_id',
        'template_id',
        'status',
        'sent_at'
    ];

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function installment() {
        return $this->belongsTo(Installment::class);
    }

    public function template() {
        return $this->belongsTo(MessageTemplate::class);
    }
}
