<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ToRais extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable =
        [
            'document_id',
            'replyTo'
        ];
    protected $casts =
        [
            'replyTo' => 'array'
        ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id', 'id');
    }
}
