<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('entreprises', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('adresse');
            $table->string('zone_intervention');
            $table->string('site_web')->nullable();
            $table->enum('taille', ['indépendant', 'moins de 10 salariés', 'entre 10 et 20 salariés', 'plus de 20 salariés']);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('entreprise_id')->nullable()->constrained('entreprises');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['entreprise_id']);
            $table->dropColumn('entreprise_id');
        });
        Schema::dropIfExists('entreprises');
    }
};