<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create( 'typology', function (Blueprint $table) {
            $table->id();
            $table->string('typology')->unique();
            $table->timestamps();
        });

        Schema::create( 'source', function (Blueprint $table) {
            $table->id();
            $table->string('source')->unique();
            $table->foreignId('typology_id')->nullable()->constrained('typology')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->text('objective');
            $table->char('status', 1)->default('C');
            $table->decimal('value', 10, 2);
            $table->foreignId('source_id')->nullable()->constrained('source')->nullOnDelete();
            $table->foreignId('program_id')->nullable()->constrained('programs')->nullOnDelete();
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('projects');
        Schema::dropIfExists('source');
        Schema::dropIfExists('typology');
    }
};
