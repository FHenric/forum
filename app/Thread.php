<?php

namespace App;

use App\RecordsActivity;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{

    use RecordsActivity;

    protected $guarded = [];

    //$with deixa explicito o que vai está sendo "puxado" quando chamamos um query de Thread, nesse caso vai esta explicito no json o criador e o canal que está sendo postado a thread
    //isso funciona reduzindo a quantidade de queries que são feitas a fim de otimizar a pagina e o carregamento dela.
    protected $with = ['creator', 'channel'];

    protected static function boot() {
        parent::boot();

        static::addGlobalScope('replyCount', function ($builder){
            $builder->withCount('replies');
        });

        static::deleting(function($thread) {
            $thread->replies()->delete();
        });
    }
    
    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }

    public function replies() 
    {
        return $this->hasMany(Reply::class);
    }

    public function creator() 
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function addReply($reply)
    {
        $this->replies()->create($reply);
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

}
