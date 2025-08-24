<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProduitImage extends Model
{
    protected $fillable =['produit_id','image_url'];

    //relation une image pour un produit

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }


}
