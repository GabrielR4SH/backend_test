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
        Schema::create('redirects', function (Blueprint $table) {
            $table->id();  // ID primário
            $table->string('destination_url');  // URL de destino (HTTPS válida)
            $table->boolean('is_active')->default(true);  // Status ativo/inativo
            $table->timestamp('last_accessed_at')->nullable();  // Último acesso
            $table->timestamps();  // created_at, updated_at
            $table->softDeletes();  // deleted_at para soft delete

            $table->index('is_active');  // Index para queries por status
            $table->index('last_accessed_at');  // Index para ordenação por último acesso
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('redirects');
    }
};
