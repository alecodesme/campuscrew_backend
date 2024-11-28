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
        Schema::create('clubs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('university_id'); // university_id INT FOREIGN KEY
            $table->string('email')->nullable(); // email STRING NULL
            $table->boolean('is_active')->default(true); // is_active BOOLEAN DEFAULT TRUE
            $table->string('tags')->nullable(); // tags STRING NULL
            $table->timestamps(); // created_at & updated_at
            $table->softDeletes();

            // Definimos las claves foráneas
            $table->foreign('university_id')->references('id')->on('universities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clubs');
    }
};
