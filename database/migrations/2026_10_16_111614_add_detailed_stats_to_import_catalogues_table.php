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
        Schema::table('import_catalogues', function (Blueprint $table) {
            $table->unsignedInteger('nb_crees')->default(0)->after('nb_lignes');
            $table->unsignedInteger('nb_mis_a_jour')->default(0)->after('nb_crees');
            $table->json('rapport_erreurs_json')->nullable()->after('nb_erreurs');
            $table->string('statut', 30)->default('termine')->after('rapport_erreurs_json');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('import_catalogues', function (Blueprint $table) {
            $table->dropColumn(['nb_crees', 'nb_mis_a_jour', 'rapport_erreurs_json', 'statut']);
        });
    }
};
