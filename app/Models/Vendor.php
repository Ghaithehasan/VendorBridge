<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $table = 'vendors';
    protected $primaryKey = 'vendor_id';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'country',
    ];

    public function vendorMaterials()
    {
        return $this->hasMany(VendorMaterial::class, 'vendor_id', 'vendor_id');
    }

    public function rawMaterials()
    {
        return $this->belongsToMany(RawMaterial::class, 'vendor_materials', 'vendor_id', 'material_id')
            ->withPivot('vendor_material_id', 'lead_time_days', 'minimum_order_qty', 'preferred_vendor', 'last_price', 'vendor_material_code')
            ->withTimestamps();
    }

    public function rfqRecipients()
    {
        return $this->hasMany(RfqRecipient::class, 'vendor_id', 'vendor_id');
    }

    public function rfqs()
    {
        return $this->belongsToMany(Rfq::class, 'rfq_recipients', 'vendor_id', 'rfq_id')
            ->withPivot('recipient_id', 'status')
            ->withTimestamps();
    }
}
