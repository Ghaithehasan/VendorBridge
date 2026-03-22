<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $table = 'units';
    protected $primaryKey = 'unit_id';

    protected $fillable = [
        'name',
        'symbol',
    ];

    public function baseRawMaterials()
    {
        return $this->hasMany(RawMaterial::class, 'base_unit_id', 'unit_id');
    }

    public function bomLines()
    {
        return $this->hasMany(Bom::class, 'unit_id', 'unit_id');
    }

    public function purchaseRequestLines()
    {
        return $this->hasMany(PurchaseRequestLine::class, 'unit_id', 'unit_id');
    }

    public function rfqs()
    {
        return $this->hasMany(Rfq::class, 'unit_id', 'unit_id');
    }
}