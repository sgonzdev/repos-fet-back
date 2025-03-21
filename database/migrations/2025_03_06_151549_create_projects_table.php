<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('status');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->text('objective');
            $table->string('source');
            $table->foreignId('program_id')->nullable()->constrained('programs')->nullOnDelete();
            $table->decimal('value', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }
};
