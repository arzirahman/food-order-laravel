<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'carts';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'cart_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'food_id',
        'user_id',
        'qty',
        'is_deleted',
        'created_by',
        'created_time',
        'modified_by',
        'modified_time',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */

    public function food()
    {
        return $this->belongsTo(Food::class, 'food_id', 'food_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
