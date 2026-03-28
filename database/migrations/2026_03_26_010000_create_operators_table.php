<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operators', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['Government', 'Private', 'Contract Carriage'])->default('Private');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_public')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
            $table->index('type');
            $table->index('is_active');
        });

        Schema::create('buses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operator_id')->constrained('operators')->cascadeOnDelete();
            $table->string('bus_name');
            $table->string('bus_number')->unique();
            $table->string('bus_number_code')->unique();
            $table->enum('category', ['Sleeper', 'Seater', 'AC', 'Ordinary'])->default('Ordinary');
            $table->string('bus_color')->nullable();
            $table->unsignedInteger('total_seats');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('operator_id');
            $table->index('is_active');
        });

        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_id')->constrained('buses')->cascadeOnDelete();
            $table->foreignId('route_id')->constrained('routes')->cascadeOnDelete();
            $table->time('departure_time');
            $table->time('arrival_time');
            $table->json('days_of_week');
            $table->enum('status', ['Active', 'Cancelled', 'Delayed'])->default('Active');
            $table->timestamps();
            $table->softDeletes();

            $table->index('bus_id');
            $table->index('route_id');
            $table->index(['bus_id', 'departure_time']);

            // allow re-create after soft delete
            $table->unique(['bus_id', 'route_id', 'departure_time', 'deleted_at'], 'trips_bus_route_departure_deleted_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trips');
        Schema::dropIfExists('buses');
        Schema::dropIfExists('operators');
    }
};
