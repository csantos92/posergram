@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        <h1>Mis favs</h1>
        <hr/>

        @foreach($likes as $like)
            @include('includes.card', ['image'=>$like->image])
        @endforeach

        <!-- Paginación -->
        <div class="clearfix"></div>
        {{$likes->links()}}

        </div>
    </div>
</div>
@endsection
