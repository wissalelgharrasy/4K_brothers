<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique(); // ex: CMD-20261106-001
            $table->string('nom');
            $table->string('telephone');
            $table->string('adresse');
            $table->text('note')->nullable();
            $table->json('items');           // snapshot des articles commandés
            $table->decimal('total', 10, 2);
            $table->enum('statut', ['nouvelle', 'contacté', 'confirmée', 'annulée'])->default('nouvelle');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
