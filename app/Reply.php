<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Favoritable;

class Reply extends Model
{

    use Favoritable, RecordsActivity;

    protected $guarded = [];

    protected $with = ['owner', 'favorites'];
    
    public function owner()
    {
        //o segundo parametro funciona como um identificador para esse owner, de forma que o belongsTo não entenda a função como 'owner_id' na hora de byscar no banco de dadoss
        return $this->belongsTo(User::class, 'user_id');
    }

   
}
