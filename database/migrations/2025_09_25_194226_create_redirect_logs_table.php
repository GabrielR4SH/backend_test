<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('redirect_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('redirect_id')->constrained()->onDelete('cascade');
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('referer')->nullable();
            $table->json('query_params')->nullable();
            $table->timestamp('accessed_at')->useCurrent();
            $table->timestamps(); // Add created_at and updated_at
            $table->index('redirect_id');
            $table->index('accessed_at');
            $table->index('ip_address');
            $table->index('referer');
        });
    }

    public function down()
    {
        Schema::dropIfExists('redirect_logs');
    }
};
