<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  //protected access without login
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
       $data = $request->validate([
            'caption' => 'required',
            'image' => 'required|image',
        ]);
        
        $imagePath = $data['image']->store('uploads', 'public'); // insert image data into public uploads directory

        // Intervention image  --- resize post image (square)
        $image = Image::make(public_path("storage/{$imagePath}"))->fit(1200,1200);
        $image->save();

        auth()->user()->posts()->create([ // userdata is automatically inserted
            'caption' => $data['caption'],
            'image' => $imagePath,
        ]);     

        return redirect('/profile/'.auth()->user()->id);
    }

    public function show(\App\Post $post) // \App\Post : fetch post data using post table id column
    {
        return view('posts.show',compact('post'));
    }
}
