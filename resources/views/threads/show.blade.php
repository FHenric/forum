@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="level">
                        <span class="flex">
                            <a href="{{route('profile', $thread->creator)}}">{{$thread->creator->name}}</a> Posted:
                            {{$thread->title}}
                        </span>

                        {{-- can é usado com policy, justamente do mesmo jeito que authorize(), primeiro o metodo, depois o model que está associado --}}
                        @can('update', $thread)
                            <form action="{{$thread->path()}}" method="POST">
                                {{csrf_field()}}
                                {{method_field('DELETE')}}

                            <button type="submit" class="btn btn-link">Delete</button>
                        </form>
                        @endcan
                    </div>
                </div>

                <div class="card-body mb-2">
                    {{$thread->body}}
                </div>
            </div>


            @foreach ($replies as $reply)
                @include('threads.reply')
            @endforeach

            {{$replies->links()}}

            @if (auth()->check())

                <form action="{{$thread->path().'/replies'}}" method="POST">
                    @csrf
                    <textarea name="body" id="body" class="form-control" rows="5" placeholder="Have something to say?"></textarea>
                
                    <button type="submit" class="btn btn-default mt-2">Post</button>
                </form>

                @else

            If you want to reply this thread, <a href="/login">Login</a>

            @endif
            
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <p>
                        {{-- cuidado com a quantidade de querys, cada {{$thread}} está fazendo uma query, o que pode pesar depois --}}
                        This thread was published {{$thread->created_at->diffForHumans()}} by
                        <a href="#">{{$thread->creator->name}}</a>, and currently has {{$thread->replies_count}} {{Str::plural('comment', $thread->replies_count )}}.
                    </p>
                </div>
            </div>
       </div>
    </div>
    

    
</div>
@endsection
