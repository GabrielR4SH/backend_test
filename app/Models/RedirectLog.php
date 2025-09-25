<?php  // Modelo para Logs

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RedirectLog extends Model {
    use HasFactory;

    // Campos
    protected $fillable = ['redirect_id', 'ip_address', 'user_agent', 'referer', 'query_params', 'accessed_at'];

    // Não usa created/updated_at, usa accessed_at
    public $timestamps = false;

    // Relação N:1 com Redirect
    public function redirect(): BelongsTo {
        return $this->belongsTo(Redirect::class);
    }
}
