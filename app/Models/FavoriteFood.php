<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteFood extends Model
{
    use HasFactory;

    protected $primaryKey = null;

    public $incrementing = false;

    protected $fillableprimaryKey = null;

    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'favorite_foods';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'food_id',
        'user_id',
        'is_favorite',
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
