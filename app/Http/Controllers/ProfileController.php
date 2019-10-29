<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    public function home()
    {
        $id = Auth::id();
        // return view('welcome',compact('id'));
        return redirect("/profile/{$id}");
    }
    public function index(User $user)
    {
        $follows = (auth()->user()) ? auth()->user()->following->contains($user->id) : false;
        // $user = User::findOrFail($user);
        return view('profiles.index',compact('user', 'follows'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user->profile); //repel access of edit page without login
        return view('profiles.edit',compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'url' => 'url',
            'image' => '',
        ]);

        if($request['image'])
        {
            $imagePath = $request['image']->store('profile', 'public'); // insert image data into public uploads directory
            $image = Image::make(public_path("storage/{$imagePath}"))->fit(1000,1000); // Intervention image  --- resize post image (square)
            $image->save();

            $imageArray = ['image' => $imagePath];
        }

        auth()->user()->profile->update(array_merge(
            $data,
            $imageArray ?? [],  // [] : not updated user image
        ));
        
        return redirect("/profile/{$user->id}");
    }
}
