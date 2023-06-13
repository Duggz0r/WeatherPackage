<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ip_addresses', function (Blueprint $table): void {
            $table->id();
            $table->string('ip_address');
            $table->string('lat');
            $table->string('lon');
            $table->string('status');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ip_addresses');
    }
};
