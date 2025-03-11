<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('researchers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('project_researcher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('researcher_id')->constrained('researchers')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('project_researcher');
        Schema::dropIfExists('researchers');
    }
};