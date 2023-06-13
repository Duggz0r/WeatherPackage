<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forecasts', function (Blueprint $table): void {
            $table->id();
            $table->date('date');
            $table->string('location');
            $table->string('temperature');
            $table->string('description');
            $table->string('icon');
            $table->unsignedBigInteger('ip_address_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forecasts');
    }
};
