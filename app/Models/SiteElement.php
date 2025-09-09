<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteElement extends Model

{   
    use HasFactory;

    protected $fillable =['name','description','type','content','status', 'site_element_categorie_id'];


    //relation un  elements du site et pour unecategorie
    public function siteElementCategorie()
    {
        return $this->belongsTo(SiteElementCategorie::class, 'site_element_categorie_id');
    }

}
