<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'size',
        'extension',
        'folder',
        'document_id',
        'user_id'
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
