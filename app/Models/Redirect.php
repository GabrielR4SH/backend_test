<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Redirect extends Model
{
    use HasFactory, SoftDeletes;

    // Campos mass assignable
    protected $fillable = ['destination_url', 'is_active'];

    // Adiciona attribute 'code' em JSON
    protected $appends = ['code'];

    // Relação 1:N com logs
    public function logs(): HasMany {
        return $this->hasMany(RedirectLog::class);
    }

    // Attribute para code (hashid)
    public function getCodeAttribute(): string {
        return Hashids::encode($this->id);
    }

    // Busca por code (decodifica hashid)
    public static function findByCode(string $code): ?self {
        try {
            $id = Hashids::decode($code)[0];
            return self::find($id);
        } catch (\Exception $e) {
            return null;
        }
    }

    // Atualiza último acesso
    public function updateLastAccessed(): void {
        $this->update(['last_accessed_at' => now()]);
    }
}
