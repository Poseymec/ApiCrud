<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ProduitImageController;  
use App\Http\Controllers\ContactController;
use App\Http\Controllers\NewsLetterController;
use App\http\Controllers\SiteElementController;
use App\http\Controllers\SiteElementCategorieController;




// categories routes

/*Route::post('/categories', [CategorieController::class, 'store']);
Route::get('/categories', [CategorieController::class, 'index']);
Route::get('/categories/{id}', [CategorieController::class, 'show']);
Route::put('/categories/{id}', [CategorieController::class, 'update']);
Route::delete('/categories/{id}', [CategorieController::class, 'destroy']);

// produits routes
Route::post('/produits', [ProduitController::class, 'store']);
Route::get('/produits', [ProduitController::class, 'index']);
Route::get('/produits/{id}', [ProduitController::class, 'show']);
Route::put('/produits/{id}', [ProduitController::class, 'update']);
Route::delete('/produits/{id}', [ProduitController::class, 'destroy']);


//produit images routes

Route::delete('/produit-images/{id}', [ProduitImageController::class, 'destroy']);


//Site Elements routes
Route::post('/site-elements', [SiteElementController::class, 'store']);
Route::get('/site-elements', [SiteElementController::class, 'index']);
Route::get('/site-elements/{id}', [SiteElementController::class, 'show']);
Route::put('/site-elements/{id}', [SiteElementController::class, 'update']);
Route::delete('/site-elements/{id}', [SiteElementController::class, 'destroy']);

//Contact routes
Route::post('/contacts', [ContactController::class, 'store']);
Route::get('/contacts', [ContactController::class, 'index']);
Route::get('/contacts/{id}', [ContactController::class, 'show']);   
Route::put('/contacts/{id}', [ContactController::class, 'update']);
Route::delete('/contacts/{id}', [ContactController::class, 'destroy']);


//NewsLetter routes
Route::post('/news-letters', [NewsLetterController::class, 'store']);
Route::get('/news-letters', [NewsLetterController::class, 'index']);
Route::get('/news-letters/{id}', [NewsLetterController::class, 'show']);
Route::put('/news-letters/{id}', [NewsLetterController::class, 'update']);
Route::delete('/news-letters/{id}', [NewsLetterController::class, 'destroy']);

*/

// route pour gerer les categories produits et images des produits

Route::apiResource('categories', CategorieController::class);
Route::apiResource('produits', ProduitController::class);
Route::apiResource('SiteElementCategories', SiteElementCategorieController::class);
Route::apiResource('site-elements', SiteElementController::class);
Route::apiResource('contacts', ContactController::class);
Route::apiResource('news-letters', NewsLetterController::class);
Route::patch('produits/{produit}/toggle-status', [ProduitController::class, 'toggleStatus']);