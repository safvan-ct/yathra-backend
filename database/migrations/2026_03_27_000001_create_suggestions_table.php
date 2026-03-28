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
        Schema::create('suggestions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Polymorphic relation
            $table->string('suggestable_type');
            $table->unsignedBigInteger('suggestable_id')->nullable();

            $table->json('proposed_data');

            $table->string('status')->default('Pending');

            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('review_note')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('user_id');
            $table->index('status');
            $table->index(['suggestable_type', 'suggestable_id']);
        });

        Schema::create('user_rewards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('points')->unsigned();
            $table->enum('activity_type', ['new_entry', 'verification']);

            // Link to suggestion to prevent duplicate
            $table->foreignId('source_id')->constrained('suggestions')->cascadeOnDelete();

            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->unique('source_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_rewards');
        Schema::dropIfExists('suggestions');
    }
};
