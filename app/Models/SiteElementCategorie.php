<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class SiteElementCategorie extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // relation entre une categorie et plusieurs elements du site
    public function siteElements()
    {
        return $this->hasMany(SiteElement::class, 'site_element_categorie_id');
    }
}
