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
    Schema::create('conversations', function (Blueprint $table) {
        $table->id();
        $table->string('name')->nullable(); // nama group, null kalau private
        $table->enum('type', ['private', 'group'])->default('private');
        $table->timestamps();
    });

    // Tabel pivot: siapa saja anggota conversation
    Schema::create('conversation_user', function (Blueprint $table) {
        $table->id();
        $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
