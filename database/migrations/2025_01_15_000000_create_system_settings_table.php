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
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, json, boolean, integer
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Inserir configurações padrão da Evolution API
        DB::table('system_settings')->insert([
            [
                'key' => 'evolution_api_url',
                'value' => 'https://evolution.iaconversas.com',
                'type' => 'string',
                'description' => 'URL base da Evolution API',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'evolution_api_key',
                'value' => '5863c643c8bf6d84e8da8bb564ea13fc',
                'type' => 'string',
                'description' => 'Chave de API da Evolution',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'n8n_webhook_url',
                'value' => 'https://n8n.iaconversas.com',
                'type' => 'string',
                'description' => 'URL base do n8n para webhooks',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};