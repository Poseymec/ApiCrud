<?php

namespace App\Models;

use App\Models\Produit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class ProduitImage extends Model
{
    use HasFactory;

    protected $fillable =['produit_id','image_path','is_cover'];

    //relation une image pour un produit

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }


}
