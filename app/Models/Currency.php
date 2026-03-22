<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $table = 'currencies';

// Currency.php
protected $fillable = ['code', 'name', 'symbol'];

public function rfqs()
{
    return $this->hasMany(Rfq::class, 'currency_id', 'id');
}

public function vendorMaterials()
{
    return $this->hasMany(VendorMaterial::class, 'currency_id', 'id');
}
}

