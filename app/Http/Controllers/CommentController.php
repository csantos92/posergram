<?php

namespace App\Http\Controllers;

//Imports
use Illuminate\Http\Request;
use App\Comment;

class CommentController extends Controller
{
    public function __construct()
    {
        //Check if user is identified
        $this->middleware('auth');
    }

    public function save(Request $request)
    {

        //Validate data
        $validate = $this->validate($request, [
            'image_id' => ['required', 'int'],
            'content' => ['required', 'string'],
        ]);

        //Get identified user
        $user = \Auth::user();

        //Get comment params
        $image_id = $request->input('image_id');
        $content = $request->input('content');

        //Create comment object
        $comment = new Comment();
        $comment->user_id = $user->id;
        $comment->image_id = $image_id;
        $comment->content = $content;

        //Save comment object into DB
        $comment->save();

        //Redirect when finished
        return redirect()->route('image.detail', ['id' => $image_id])
            ->with([
                'message' => 'Has publicado tu comentario correctamente'
            ]);
    }

    public function delete($id)
    {
        //Get identified user
        $user = \Auth::user();

        //Get comment object
        $comment = Comment::find($id);

        //Check if comment belongs to the user
        if ($user && ($comment->user_id == $user->id || $comment->image->user_id == $user->id)) {
            $comment->delete();

            //Redirect
            return redirect()->route('image.detail', ['id' => $comment->image->id])
                ->with([
                    'message' => 'Has eliminado tu comentario correctamente'
                ]);
        } else {
            //Redirect
            return redirect()->route('image.detail', ['id' => $comment->image->id])
                ->with([
                    'message' => 'El comentario no se ha podido eliminar'
                ]);
        }
    }
}
