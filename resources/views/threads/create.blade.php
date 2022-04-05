@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create a New Thread</div>

                <div class="card-body">
                    <form method='POST' action="/threads">
                        {{ csrf_field() }}

                        <div class='form-group'>
                            <label for="channel">Choose a Channel:</label>
                                <select name="channel_id" id="channel_id" class="form-control" required>
                                    <option value="">Choose one</option>
                                    @foreach ($channels as $channel)
                                        <option value="{{$channel->id}}" {{old('channel_id') == $channel->id ? 'selected' : ''}}>{{$channel->name}}</option>
                                    @endforeach
                                </select>                        
                        </div>

                        <div class='form-group'>
                             <label for="title">Title:</label>
                             <input type="text" class="form-control" id="title" name="title" placeholder="Type a title for your thread" value="{{old('title')}}" required>
                         </div>
                                                               
                        <div class="form-group">
                             <label for="body">Body:</label>
                             <textarea rows="8" name= "body" id="body" class="form-control" value="{{old('body')}}" required></textarea>
                         </div>

                         <div>
                            <button type="submit" class="btn btn-default">Publish</button>
                         </div>

                         @if (count($errors))
                         <ul class="alert alert-danger">
                             @foreach ($errors->all() as $error)
                                 <li>{{$error}}</li>
                             @endforeach
                         </ul>
                     @endif
                     </form>

                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
