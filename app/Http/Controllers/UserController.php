<?php

namespace App\Http\Controllers;

//Imports
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\User;

class UserController extends Controller
{
    public function __construct()
    {
        //Checks if user is identified
        $this->middleware('auth');
    }

    public function config()
    {
        return view('user.config');
    }

    public function index($search = null)
    {
        //Searcher
        if (!empty($search)) {
            $users = User::where('nick', 'LIKE', '%' . $search . '%')
                ->orWhere('name', 'LIKE', '%' . $search . '%')
                ->orWhere('surname', 'LIKE', '%' . $search . '%')
                ->orderBy('id', 'desc')
                ->paginate(5);
        } else {
            $users = User::orderBy('id', 'desc')->paginate(5);
        }

        return view('user.index', [
            'users' => $users
        ]);
    }

    public function update(Request $request)
    {

        //Get identified user
        $user = \Auth::user();
        $id = $user->id;

        //Validate data
        $validate = $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'nick' => ['required', 'string', 'max:255', 'unique:users,nick,' . $id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
        ]);

        //Get form data
        $name = $request->input('name');
        $surname = $request->input('surname');
        $nick = $request->input('nick');
        $email = $request->input('email');

        //Asign values to the object
        $user->name = $name;
        $user->surname = $surname;
        $user->nick = $nick;
        $user->email = $email;

        //Upload image
        $image_path = $request->file('image_path');
        if ($image_path) {
            //Set unique name
            $image_path_name = time() . $image_path->getClientOriginalName();

            //Save image in users folder
            Storage::disk('users')->put($image_path_name, File::get($image_path));

            //Save image path into DB
            $user->image = $image_path_name;
        }

        //Update user object
        $user->update();

        //Redirect
        return redirect()->route('config')
            ->with(['message' => 'Usuario actualizado correctamente']);
    }

    public function getImage($filename)
    {
        //Get image by name
        $file = Storage::disk('users')->get($filename);
        return new Response($file, 200);
    }

    public function profile($id)
    {
        //Find user by id
        $user = User::find($id);

        //Return view with user
        return view('user.profile', [
            'user' => $user
        ]);
    }
}
