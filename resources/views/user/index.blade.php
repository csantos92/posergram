@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

        <h1>Usuarios</h1><hr><br>
        <form method="GET" action="{{ route('user.index') }}" id="search">

        <div class="row">
            <div class="form-group col">
              <input type="text" id="search-box" class="form-control"/>
            </div>
            <div class="form-group col">
              <input type="submit" value="Buscar usuario" class="btn btn-dark"/>
            </div>
        </div>

        </form>
        <hr/><br>
        
        @foreach($users as $user)
            <div class="profile-user">
            
            @if($user->image)
                    <div class="container-avatar">
                        <img src="{{ route('user.avatar', ['filename'=>$user->image]) }}" class="avatar" />
                    </div>
                @endif
                
                <div class="user-info">
                    <h2>{{'@'.$user->nick}}</h2>
                    <p>{{'Se unió: '.\FormatTime::LongTimeFilter($user->created_at) }}</p>
                    <a href="{{ route('profile', ['id' => $user->id]) }}" class="btn btn-sm btn-success">Ver perfil</a>
                </div>
            </div>
        @endforeach

        <!-- Paginación -->
        <div class="clearfix"></div>
        {{$users->links()}}
        
        </div>
    </div>
</div>
@endsection
