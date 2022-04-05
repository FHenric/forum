@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="page-header">
                    <h1 class="display-4 mb-4">
                        {{$profileUser->name}}
                        <small class="text-muted">{{$profileUser->created_at->diffForHumans()}}</small>
                    </h1>
                </div>
        
                @foreach ($threads as $thread)
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="level">
                            <span class="flex">
                                {{$thread->creator->name}} Posted:
                                <a href="{{$thread->path()}}">{{$thread->title}}</a>
                            </span>
        
                            <span>
                                {{$thread->created_at->diffForHumans()}}
                            </span>
                        </div>
                    </div>
        
                    <div class="card-body mb-2">
                        {{$thread->body}}
                    </div>
                </div>
                @endforeach
        
                {{$threads->links()}}
            </div>
        </div>

        

    </div>
@endsection