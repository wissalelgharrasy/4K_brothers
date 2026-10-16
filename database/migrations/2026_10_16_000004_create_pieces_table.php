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
        Schema::create('pieces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicule_id')->nullable()->constrained('vehicules')->nullOnDelete();
            $table->foreignId('famille_id')->constrained('familles')->cascadeOnDelete();
            $table->string('designation');
            $table->unsignedSmallInteger('annee_debut');
            $table->enum('position', ['AV', 'AR', 'CTRL', 'RL']);
            $table->string('variante')->nullable();
            $table->decimal('prix_ttc', 10, 2);
            $table->boolean('dispo_droite')->nullable();
            $table->boolean('dispo_gauche')->nullable();
            $table->boolean('en_stock')->nullable();
            $table->string('image')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();

            // Indexes for fast searching & filtering
            $table->index(['vehicule_id', 'actif']);
            $table->index('famille_id');
            $table->index('position');
            $table->index('annee_debut');
            $table->index('designation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pieces');
    }
};
