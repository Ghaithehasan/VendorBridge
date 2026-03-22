<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequestLine extends Model
{
    use HasFactory;

    protected $table = 'pr_lines';
    protected $primaryKey = 'pr_line_id';

    protected $fillable = [
        'pr_id',
        'line_no',
        'material_id',
        'quantity',
        'unit_id',
        'required_delivery_date',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'required_delivery_date' => 'date',
    ];

    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class, 'pr_id', 'pr_id');
    }

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class, 'material_id', 'material_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'unit_id');
    }

    public function rfq()
    {
        return $this->hasOne(Rfq::class, 'pr_line_id', 'pr_line_id');
    }
}
