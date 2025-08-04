<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Obter uma configuração pelo key
     */
    public static function get(string $key, $default = null)
    {
        $cacheKey = "system_setting_{$key}";
        
        return Cache::remember($cacheKey, 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            
            if (!$setting) {
                return $default;
            }
            
            return self::castValue($setting->value, $setting->type);
        });
    }

    /**
     * Definir uma configuração
     */
    public static function set(string $key, $value, string $type = 'string', string $description = null)
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => self::prepareValue($value, $type),
                'type' => $type,
                'description' => $description,
            ]
        );

        // Limpar cache
        Cache::forget("system_setting_{$key}");

        return $setting;
    }

    /**
     * Preparar valor para armazenamento
     */
    protected static function prepareValue($value, string $type)
    {
        switch ($type) {
            case 'json':
                return json_encode($value);
            case 'boolean':
                return $value ? '1' : '0';
            default:
                return (string) $value;
        }
    }

    /**
     * Converter valor do banco para o tipo correto
     */
    protected static function castValue($value, string $type)
    {
        switch ($type) {
            case 'json':
                return json_decode($value, true);
            case 'boolean':
                return (bool) $value;
            case 'integer':
                return (int) $value;
            default:
                return $value;
        }
    }

    /**
     * Obter todas as configurações da Evolution API
     */
    public static function getEvolutionSettings()
    {
        return [
            'api_url' => self::get('evolution_api_url', 'https://evolution.iaconversas.com'),
            'api_key' => self::get('evolution_api_key', ''),
            'n8n_url' => self::get('n8n_webhook_url', 'https://n8n.iaconversas.com'),
        ];
    }

    /**
     * Atualizar configurações da Evolution API
     */
    public static function updateEvolutionSettings(array $settings)
    {
        if (isset($settings['api_url'])) {
            self::set('evolution_api_url', $settings['api_url'], 'string', 'URL base da Evolution API');
        }
        
        if (isset($settings['api_key'])) {
            self::set('evolution_api_key', $settings['api_key'], 'string', 'Chave de API da Evolution');
        }
        
        if (isset($settings['n8n_url'])) {
            self::set('n8n_webhook_url', $settings['n8n_url'], 'string', 'URL base do n8n para webhooks');
        }
    }
}