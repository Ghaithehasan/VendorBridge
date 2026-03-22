<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RfqRecipient extends Model
{
    use HasFactory;

    protected $table = 'rfq_recipients';
    protected $primaryKey = 'recipient_id';

    protected $fillable = [
        'rfq_id',
        'vendor_id',
        'status',
    ];

    public function rfq()
    {
        return $this->belongsTo(Rfq::class, 'rfq_id', 'rfq_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'vendor_id');
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class, 'recipient_id', 'recipient_id');
    }
}
