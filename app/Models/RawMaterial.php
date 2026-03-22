<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    use HasFactory;

    protected $table = 'raw_materials';
    protected $primaryKey = 'material_id';

    protected $fillable = [
        'name',
        'base_unit_id',
    ];

    public function baseUnit()
    {
        return $this->belongsTo(Unit::class, 'base_unit_id', 'unit_id');
    }

    public function bomLines()
    {
        return $this->hasMany(Bom::class, 'material_id', 'material_id');
    }

    public function vendorMaterials()
    {
        return $this->hasMany(VendorMaterial::class, 'material_id', 'material_id');
    }

    public function vendors()
    {
        return $this->belongsToMany(Vendor::class, 'vendor_materials', 'material_id', 'vendor_id')
            ->withPivot('vendor_material_id', 'lead_time_days', 'minimum_order_qty', 'preferred_vendor', 'last_price', 'vendor_material_code')
            ->withTimestamps();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'bom', 'material_id', 'product_id')
            ->withPivot('bom_id', 'quantity_required', 'unit_id')
            ->withTimestamps();
    }

    public function purchaseRequestLines()
    {
        return $this->hasMany(PurchaseRequestLine::class, 'material_id', 'material_id');
    }

    public function rfqs()
    {
        return $this->hasMany(Rfq::class, 'material_id', 'material_id');
    }
}