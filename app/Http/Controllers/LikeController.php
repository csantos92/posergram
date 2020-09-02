<?php

namespace App\Http\Controllers;

//Imports
use Illuminate\Http\Request;
use App\Like;

class LikeController extends Controller
{
    public function __construct()
    {
        //Check if the user is identified
        $this->middleware('auth');
    }


    public function index()
    {
        //Get identified user
        $user = \Auth::user();

        //Get likes by user ID
        $likes = Like::where('user_id', $user->id)->orderBy('id', 'desc')->paginate(5);

        //Return view with variable likes
        return view('like.index', [
            'likes' => $likes
        ]);
    }

    public function like($image_id)
    {
        //Get identified user
        $user = \Auth::user();

        //Check if there is a like made by user
        $isset_like = Like::where('user_id', $user->id)
            ->where('image_id', $image_id)
            ->count();

        //In case there is not a like it creates it
        if ($isset_like == 0) {
            $like = new Like();
            $like->user_id = $user->id;
            $like->image_id = (int)$image_id;

            //Save like into DB
            $like->save();

            //Return variable
            return response()->json([
                'like' => $like
            ]);
        } else {
            //Return message
            return response()->json([
                'message' => 'No puedes hacer like dos veces a un mismo post'
            ]);
        }
    }

    public function dislike($image_id)
    {
        //Get identified user
        $user = \Auth::user();

        //Get like made by user
        $like = Like::where('user_id', $user->id)
            ->where('image_id', $image_id)
            ->first();

        if ($like) {
            //Deletes like from DB
            $like->delete();

            //Return message
            return response()->json([
                'like' => $like,
                'message' => 'Has hecho dislike correctamente'
            ]);
        } else {
            //Return message
            return response()->json([
                'message' => 'El like no existe'
            ]);
        }
    }
}
