<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReplyToDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable =
        [
            'to',
            'from',
            'document_id',
            'reply_document_id'
        ];


    public function shareDocument(): BelongsTo
    {
        return $this->belongsTo(ShareDocument::class);
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
