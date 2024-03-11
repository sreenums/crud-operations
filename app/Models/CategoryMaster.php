<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryMaster extends Model
{
    use HasFactory;

    public static function getCategoryList()
    {
        return static::select('id', 'category')->distinct()->get();
    }

}
