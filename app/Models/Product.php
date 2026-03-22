<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'product_id';

    protected $fillable = [
        'code',
        'name',
        'description',
    ];

    public function bomLines()
    {
        return $this->hasMany(Bom::class, 'product_id', 'product_id');
    }

    public function rawMaterials()
    {
        return $this->belongsToMany(RawMaterial::class, 'bom', 'product_id', 'material_id')
            ->withPivot('bom_id', 'quantity_required', 'unit_id')
            ->withTimestamps();
    }
}
