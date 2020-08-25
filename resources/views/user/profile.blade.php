@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        <h1>Perfil de {{$user->name.' '.$user->surname}}</h1>
        <hr/>

        <div class="profile-user">
            
        @if($user->image)
                <div class="container-avatar">
                    <img src="{{ route('user.avatar', ['filename'=>$user->image]) }}" class="avatar" />
                </div>
            @endif
            
            <div class="user-info">
                <h1>{{'@'.$user->nick}}</h1>
                <p>{{'Se unió: '.\FormatTime::LongTimeFilter($user->created_at) }}</p>
            </div>
        </div>

        <hr/>
        <div class="clearfix"></div>
        
        @foreach($user->images as $image)
           @include('includes.card', ['image'=>$image])
        @endforeach

        </div>
    </div>
</div>
@endsection
