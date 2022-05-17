<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [ 'name', 'image', 'price', 'description' ];

    public function Categories()
    {
        return $this->belongsToMany(Category::class,'category_menu');
    }
}
