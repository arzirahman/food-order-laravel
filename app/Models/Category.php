<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'category_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_name',
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
        return $this->hasMany(Food::class, 'food_id', 'food_id');
    }
}
