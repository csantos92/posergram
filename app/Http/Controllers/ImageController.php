<?php

namespace App\Http\Controllers;

//Imports
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Image;
use App\Comment;
use App\Like;

class ImageController extends Controller
{
    public function __construct()
    {
        //Check if user is identified
        $this->middleware('auth');
    }

    public function upload()
    {
        return view('image/upload');
    }

    public function save(Request $request)
    {

        //Validation
        $validate = $this->validate($request, [
            'description' => ['required'],
            'image_path' => ['required', 'image']
        ]);

        //Get params
        $image_path = $request->file('image_path');
        $description = $request->input('description');

        //Get identified user
        $user = \Auth::user();

        //Create object
        $image = new Image();
        $image->user_id = $user->id;
        $image->description = $description;

        //Upload image
        if ($image_path) {
            $image_path_name = time() . $image_path->getClientOriginalName();
            Storage::disk('images')->put($image_path_name, File::get($image_path));
            $image->image_path = $image_path_name;
        }

        //Save object into DB
        $image->save();

        //Redirect
        return redirect()->route('home')
            ->with([
                'message' => 'La foto ha sido subida correctamente'
            ]);
    }

    public function getImage($filename)
    {
        //Get image by name
        $file = Storage::disk('images')->get($filename);

        return new Response($file, 200);
    }

    public function detail($id)
    {
        //Find image by ID
        $image = Image::find($id);

        return view('image.detail', [
            'image' => $image
        ]);
    }

    public function delete($id)
    {
        //Get identified user
        $user = \Auth::user();

        //Get image by id
        $image = Image::find($id);

        //Get comment and likes
        $comments = Comment::where('image_id', $id)->get();
        $likes = Like::where('image_id', $id)->get();

        //Validation
        if ($user && $image && $image->user->id == $user->id) {

            if ($comments && count($comments) >= 1) {
                foreach ($comments as $comment) {
                    //Delete comment
                    $comment->delete();
                }
            }

            if ($likes && count($likes) >= 1) {
                foreach ($likes as $like) {
                    //Delete like
                    $like->delete();
                }
            }

            //Delete image
            Storage::disk('images')->delete($image->image_path);

            $image->delete();

            //Return message
            $message = array('message' => 'La imagen se ha borrado correctamente');
        } else {
            $message = array('message' => 'La imagen no se ha borrado correctamente');
        }

        //Redirect with message
        return redirect()->route('home')->with($message);
    }

    public function edit($id)
    {
        //Get identified user and image by ID
        $user = \Auth::user();
        $image = Image::find($id);

        //Validation
        if ($user && $image && $image->user->id == $user->id) {
            return view('image.edit', [
                'image' => $image
            ]);
        } else {
            return redirect()->route('home');
        }
    }

    public function update(Request $request)
    {
        //Validation
        $validate = $this->validate($request, [
            'image_path' => ['image']
        ]);

        //Get params
        $image_id = $request->input('image_id');
        $image_path = $request->file('image_path');
        $description = $request->input('description');

        //Find image by id
        $image = Image::find($image_id);
        $image->description = $description;

        //Upload image
        if ($image_path) {
            $image_path_name = time() . $image_path->getClientOriginalName();
            Storage::disk('images')->put($image_path_name, File::get($image_path));
            $image->image_path = $image_path_name;
        }

        //Update image
        $image->update();

        //Redirect
        return redirect()->route('image.detail', ['id' => $image_id])
            ->with(['message' => 'Imagen actualizada con éxito']);
    }
}
