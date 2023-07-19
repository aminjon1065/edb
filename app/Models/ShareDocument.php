<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShareDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable =
        [
            'uuid',
            'to',
            'from',
            'opened',
            'document_id',
            'toRais',
            'isReply'
        ];

    protected $casts =
        [
            'uuid' => 'string',
            'opened' => 'boolean',
            'toRais' => 'boolean',
            'isReply' => 'boolean'
        ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function toUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to', 'id');
    }

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from', 'id');
    }

}
