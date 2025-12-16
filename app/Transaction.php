<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'transaction_id',
        'order_ref_num',
        'mobile_number',
        'amount',
        'status',
        'easypaisa_transaction_id',
        'response_code',
        'callback_data',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'callback_data' => 'array',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the status badge class
     *
     * @return string
     */
    public function getStatusBadgeClassAttribute()
    {
        switch ($this->status) {
            case 'pending':
                return 'badge-warning';
            case 'success':
            case 'completed':
                return 'badge-success';
            case 'failed':
                return 'badge-danger';
            default:
                return 'badge-secondary';
        }
    }

    /**
     * Check if transaction is successful
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return in_array($this->status, ['success', 'completed']) && 
               $this->response_code === '0000';
    }

    /**
     * Scope for successful transactions
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success')
                    ->where('response_code', '0000');
    }

    /**
     * Scope for pending transactions
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}

