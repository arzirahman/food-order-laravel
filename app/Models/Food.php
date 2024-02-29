<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'foods';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'food_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'food_name',
        'image_filename',
        'price',
        'ingridient',
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

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function favorite_food()
    {
        return $this->hasMany(FavoriteFood::class);
    }

    public function cart()
    {
        return $this->hasMany(Cart::class, 'food_id', 'food_id');
    }
}
