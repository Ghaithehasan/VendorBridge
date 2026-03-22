<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rfq extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'rfq';
    protected $primaryKey = 'rfq_id';

    protected $fillable = [
        'pr_line_id',
        'rfq_number',
        'material_id',
        'quantity',
        'unit_id',
        'required_delivery_date',
        'currency_id',
        'rfq_date',
        'quotation_due_date',
        'payment_terms',
        'delivery_location',
        'pdf_path',
        'issued_by',
        'issued_at',
        'status',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'required_delivery_date' => 'date',
        'rfq_date' => 'date',
        'quotation_due_date' => 'date',
        'issued_at' => 'datetime',
    ];

    public function purchaseRequestLine()
    {
        return $this->belongsTo(PurchaseRequestLine::class, 'pr_line_id', 'pr_line_id');
    }

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class, 'material_id', 'material_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'unit_id');
    }

    public function issuer()
    {
        return $this->belongsTo(User::class, 'issued_by', 'user_id');
    }

    public function recipients()
    {
        return $this->hasMany(RfqRecipient::class, 'rfq_id', 'rfq_id');
    }

    public function vendors()
    {
        return $this->belongsToMany(Vendor::class, 'rfq_recipients', 'rfq_id', 'vendor_id')
            ->withPivot('recipient_id', 'status')
            ->withTimestamps();
    }

public function currency()
{
    return $this->belongsTo(Currency::class, 'currency_id', 'id');
}
}
