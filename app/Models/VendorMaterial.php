<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorMaterial extends Model
{
    use HasFactory;

    protected $table = 'vendor_materials';
    protected $primaryKey = 'vendor_material_id';

    protected $fillable = [
        'vendor_id',
        'material_id',
        'lead_time_days',
        'minimum_order_qty',
        'preferred_vendor',
        'last_price',
        'currency_id',
        'vendor_material_code',
    ];

    protected $casts = [
        'minimum_order_qty' => 'decimal:4',
        'preferred_vendor' => 'boolean',
        'last_price' => 'decimal:4',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'vendor_id');
    }

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class, 'material_id', 'material_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }
}
