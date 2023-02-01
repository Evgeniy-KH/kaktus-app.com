<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recipe extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'recipes';
    protected $guarded = false;

    public function RecipeImage(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RecipeImage::class, 'recipe_id','id');
    }

    public function tags(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tag::class,'recipes_tags', 'recipe_id', 'tag_id');
    }

}
