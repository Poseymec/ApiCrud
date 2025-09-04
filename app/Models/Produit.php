<?php

namespace App\Models;
use App\Models\Categorie;
use App\Models\ProduitImage;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'categorie_id', 'description1', 'description2', 'prix', 'status'];


    //relation : un produit appartient a une categorie
    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    //relation : un produit peut avoir plusieur images

    public function images()
    {
        return $this-> hasMany(ProduitImage::class);
    }

    //relation avec limage de couverture
    public function cover()
    {
        return $this->hasOne(ProduitImage::class)->where('is_cover', true);
    }
    // Scope pour les produits actifs
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
