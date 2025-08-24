<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $fillabe = ['name', 'categorie_id', 'description1', 'description2', 'prix'];


    //relation : un produit appartient a une categorie
    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    //relation : un produit peut avoir plusieur image

    public function produitImage()
    {
        return $this-> hasMany(ProduitImage::class);
    }
}
