<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('site_elements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_element_categorie_id')->constrained('site_element_categories')->onDelete('cascade');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('type'); // 'header', 'footer', 'sidebar', etc. $table->string('content'); // texte ou url des images et logo
            $table->string('status')->default('active'); // e.g., 'active', 'inactive'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_elements');
    }
};
