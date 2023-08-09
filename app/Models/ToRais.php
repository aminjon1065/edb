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
            'uuid',
            'management_id',
            'document_id',
            'replyTo',
            'opened'
        ];
    protected $casts =
        [
            'opened' => 'boolean',
            'replyTo' => 'array'
        ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id', 'id')->with(["file", "replyToDocument", "toRais"]);
    }
    public function user():BelongsTo{
        return $this->belongsTo(User::class, 'management_id', 'id');
    }
}
