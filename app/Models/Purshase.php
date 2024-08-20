<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $store_id
 * @property string $purshase_date
 * @property string $sum
 * @property string $currency
 * @property string|null $document_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Store $store
 * @method static \Database\Factories\PurshaseFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Purshase newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Purshase newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Purshase query()
 * @method static \Illuminate\Database\Eloquent\Builder|Purshase whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purshase whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purshase whereDocumentPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purshase whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purshase wherePurshaseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purshase whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purshase whereSum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purshase whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
