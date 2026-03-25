<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('senhas', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 50);
            $table->foreignId('vaqueiro_id')->constrained('vaqueiros')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('senhas');
    }
};
