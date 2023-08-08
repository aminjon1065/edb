<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ToManagement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'management_id',
        'document_id',
        'replyTo',
        'opened'
    ];
    protected $casts = [
        'replyTo' => 'array',
        'opened' => 'boolean'
    ];

    public function document():BelongsTo
    {
        return  $this->belongsTo(Document::class);
    }
}
