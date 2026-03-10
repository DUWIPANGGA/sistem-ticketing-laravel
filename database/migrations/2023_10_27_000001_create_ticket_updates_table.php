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
        Schema::create('ticket_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User who made the update
            $table->text('comment')->nullable();
            $table->enum('status', ['open', 'in_progress', 'on_hold', 'resolved', 'closed'])->nullable(); // Status change
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->nullable(); // Priority change
            $table->string('attachment')->nullable(); // New attachment added
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_updates');
    }
};