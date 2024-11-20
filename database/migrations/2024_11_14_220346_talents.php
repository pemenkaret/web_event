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
        Schema::create('talents', function (Blueprint $table) {
            $table->id(); 
            $table->string('name');
            $table->foreignId('organizer')->nullable()->constrained('organizers')->onDelete('set null');
            $table->foreignId('image')->nullable()->constrained('images')->onDelete('set null');
            $table->foreignId('role')->nullable()->constrained('roles')->onDelete('set null');
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talents');
    }
};