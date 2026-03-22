<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $table = 'quotation';
    protected $primaryKey = 'quotation_id';

    protected $fillable = [
        'recipient_id',
        'version_no',
        'unit_price',
        'lead_time_days',
        'status',
    ];

    protected $casts = [
        'unit_price' => 'decimal:4',
    ];

    public function recipient()
    {
        return $this->belongsTo(RfqRecipient::class, 'recipient_id', 'recipient_id');
    }
}
