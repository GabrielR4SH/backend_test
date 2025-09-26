<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RedirectLog extends Model
{
    use HasFactory;

    protected $table = 'redirect_logs';
    protected $fillable = ['redirect_id', 'ip_address', 'user_agent', 'referer', 'query_params', 'accessed_at'];
    protected $casts = ['query_params' => 'array', 'accessed_at' => 'datetime'];
    public $timestamps = false; // Disable timestamps
}
