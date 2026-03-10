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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->string('subject');
            $table->text('description');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['open', 'in_progress', 'on_hold', 'resolved', 'closed'])->default('open');
            $table->string('category');
            $table->string('attachment')->nullable(); // Path to the attachment
            $table->timestamp('estimated_completion_at')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User who created the ticket
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null'); // User assigned to the ticket
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};