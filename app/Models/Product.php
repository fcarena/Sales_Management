<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = array(
        'id',
        'category_id',
        'name',
        'description',
        'price',
        'remaining_amount',
        'is_on_sale',
        'create_at',
        'updated_at',
    );

    /**
     * Product belongs to Category
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }
    /**
     * Product belongs to many BillDetail
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function billDetail()
    {
        return $this->belongsToMany('App\Models\BillDetail');
    }
    /**
     * Product belongs to many OrderDetail
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderDetail()
    {
        return $this->belongsToMany('App\Models\OrderDetail');
    }
}
