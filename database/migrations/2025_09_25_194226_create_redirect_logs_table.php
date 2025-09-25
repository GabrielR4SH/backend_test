<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redirect_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('redirect_id')->constrained()->onDelete('cascade');  // FK para Redirect, cascade delete
            $table->string('ip_address');  // IP da request
            $table->string('user_agent')->nullable();  // User-Agent
            $table->string('referer')->nullable();  // Header Referer
            $table->json('query_params')->nullable();  // Query params como JSON
            $table->timestamp('accessed_at')->useCurrent();  // Data/hora do acesso

            $table->index('redirect_id');  // Index para joins
            $table->index('accessed_at');  // Index para queries por data (stats últimos 10 dias)
            $table->index('ip_address');  // Index para contagem de uniques
            $table->index('referer');  // Index para top referers
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('redirect_logs');
    }
};
