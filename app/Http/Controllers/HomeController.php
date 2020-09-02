<?php

namespace App\Http\Controllers;

//Imports
use Illuminate\Http\Request;
use App\Image;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //Check if user is identified
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //Show 5 images per page
        $images = Image::orderBy('id', 'desc')->paginate(5);

        return view('home', [
            'images' => $images
        ]);
    }
}
