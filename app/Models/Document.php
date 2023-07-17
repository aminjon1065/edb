<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'title',
        'content',
        'control',
        'status',
        'type',
        'user_id',
        'date_done'
    ];

    protected $casts = [
        'date_done' => 'datetime',
        'uuid' => 'string',
        'control' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function file(): HasMany
    {
        return $this->hasMany(File::class);
    }

    public function shareDocument(): HasMany
    {
        return $this->hasMany(ShareDocument::class);
    }
}
