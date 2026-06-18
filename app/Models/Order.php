<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'receipt_images'        => 'array',
        'order_caption'         => 'array',
        'confirmed_rejected_at' => 'datetime',
        'prd_price'             => 'integer',
        'total_price'           => 'integer',
        'prd_qty'               => 'integer',
        'msg_id'                => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'prd_id');
    }

    public function addOnProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'add_on_prd_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'chat_id', 'chat_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'processing' => 'در حال پردازش',
            'received' => 'دریافت شده',
            'confirmed' => 'تایید شده',
            'rejected' => 'رد شده',
            default => $this->status,
        };
    }

    public function getUserFeedbackLabelAttribute(): string
    {
        return match($this->user_feedback) {
            'good' => 'خوب',
            'bad' => 'بد',
            'no_comment' => 'بدون نظر',
            default => '-',
        };
    }
}
