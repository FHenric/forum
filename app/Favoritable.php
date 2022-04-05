<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 */
trait Favoritable
{
    public function favorites()
    {
        //como segundo argumento ele recebe o tipo de data, no caso a coluna do DB é o favorited_type, o laravel interpreta o type e nesse caso só colocamos o prefixo 'favorited'
       return $this->morphMany(Favorite::class, 'favorited');
    }

    public function favorite()
    {
        $attributes = ['user_id' => auth()->id()];
        
        if(! $this->favorites()->where($attributes)->exists()){
                    $this->favorites()->create($attributes);

        }
    }

    public function isFavorited()
    {
        return !! $this->favorites->where('user_id', auth()->id())->count();
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }
}
