<div class="card mb-3">

    <div class="card-header ">
       <div class="level">
        <h6 class="flex">
            <a href="{{route('profile', $reply->owner)}}" >{{$reply->owner->name}}</a> said {{$reply->created_at->diffForHumans()}}
        </h6>

       <div>
           <form method="POST" action="/replies/{{$reply->id}}/favorites">
            {{ csrf_field() }}

               <button class="btn btn-default {{$reply->isFavorited() ? 'disabled' : ''}}" type="submit">
                    {{$reply->favorites_count}} {{Str::plural('Favorite', $reply->favorites_count )}}
               </button>
           </form>
       </div>
       </div>
    </div>

    <div class="card-body">
        {{$reply->body}}
    </div>
</div>