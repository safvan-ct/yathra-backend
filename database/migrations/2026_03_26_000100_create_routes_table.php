<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('origin_id')->constrained('stations')->cascadeOnDelete();
            $table->foreignId('destination_id')->constrained('stations')->cascadeOnDelete();
            $table->string('route_code')->unique();
            $table->string('path_signature');
            $table->decimal('distance', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['origin_id', 'destination_id', 'path_signature'], 'routes_origin_destination_signature_unique');
            $table->index('origin_id');
            $table->index('destination_id');
            $table->index('path_signature');
        });

        Schema::create('route_nodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained('routes')->cascadeOnDelete();
            $table->foreignId('station_id')->constrained('stations')->cascadeOnDelete();
            $table->unsignedInteger('stop_sequence');
            $table->decimal('distance_from_origin', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['route_id', 'stop_sequence'], 'route_nodes_route_sequence_unique');
            $table->unique(['route_id', 'station_id'], 'route_nodes_route_station_unique');
            $table->index(['route_id', 'stop_sequence']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('route_nodes');
        Schema::dropIfExists('routes');
    }
};
