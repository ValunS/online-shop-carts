<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purshase extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'purshase_date',
        'sum',
        'currency',
        'document_path',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
