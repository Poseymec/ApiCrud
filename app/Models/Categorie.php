<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $fillable = ['name'];

    //relation entre les categories e les produits( une categorie peut avoir plusieurs produits)

    public function produits()
    {
        return $this->hasMany(Produit::class);
    }   
}
