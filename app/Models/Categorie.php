<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class Categorie extends Model
{

    use HasFactory;

    protected $fillable = ['name'];

    //relation entre les categories e les produits( une categorie peut avoir plusieurs produits)

    public function produits()
    {
        return $this->hasMany(Produit::class);
    }   
}
