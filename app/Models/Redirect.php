<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hashids\Hashids;
use Illuminate\Support\Facades\DB;

class Redirect extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'redirects';
    protected $fillable = ['destination_url', 'is_active', 'last_accessed_at', 'code'];
    protected $casts = ['is_active' => 'boolean'];
    private static $hashids;

    public static function boot()
    {
        parent::boot();

        static::initializeHashids();

        static::creating(function ($model) {

        });

        static::created(function ($model) {
            DB::transaction(function () use ($model) {
                if (!$model->code) {
                    $code = static::getHashids()->encode($model->id);
                    //\Log::info("Generated code {$code} for redirect ID {$model->id}");
                    $model->code = $code;
                    $model->save();
                    //\Log::info("Saved code {$code} for redirect ID {$model->id}");
                    $decoded = static::getHashids()->decode($code);
                    //\Log::info("Decoded code {$code} to ID: " . (empty($decoded) ? 'empty' : $decoded[0]));
                    if (empty($decoded) || $decoded[0] !== $model->id) {
                        //\Log::error("Hashids encoding/decoding mismatch for ID: {$model->id}, code: {$code}");
                        throw new \Exception("Hashids encoding/decoding mismatch for ID: {$model->id}, code: {$code}");
                    }
                }
            });
        });
    }

    private static function initializeHashids()
    {
        if (!self::$hashids) {
            self::$hashids = new Hashids(
                config('hashids.connections.main.salt', 'your-default-salt'),
                config('hashids.connections.main.length', 6),
                config('hashids.connections.main.alphabet', 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890')
            );
        }
    }

    private static function getHashids()
    {
        self::initializeHashids();
        return self::$hashids;
    }

    public static function findByCode($code)
    {
        //\Log::info("findByCode: Attempting to decode code {$code}");
        try {
            $decoded = static::getHashids()->decode($code);
            $id = $decoded[0] ?? null;
            //\Log::info("findByCode: Code {$code} decoded to ID: " . ($id ?? 'null'));
            if ($id) {
                $redirect = static::withTrashed()->find($id);
               // \Log::info("findByCode: Found redirect for ID {$id}: " . ($redirect ? 'Found' : 'Not found'));
                return $redirect;
            }
            //\Log::info("findByCode: No ID decoded for code {$code}");
            return null;
        } catch (\Exception $e) {
            //\Log::error("findByCode error for code {$code}: " . $e->getMessage());
            return null;
        }
    }

    public function logs()
    {
        return $this->hasMany(RedirectLog::class, 'redirect_id');
    }

    public function updateLastAccessed()
    {
        RedirectLog::create([
            'redirect_id' => $this->id,
            'accessed_at' => now(),
            'ip_address' => request()->ip() ?? '127.0.0.1',
            'user_agent' => request()->userAgent(),
            'referer' => request()->header('referer'),
            'query_params' => request()->query() ? json_encode(request()->query()) : null,
        ]);
    }
}
