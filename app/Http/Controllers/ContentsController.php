<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class ContentsController extends Controller
{
    //
    public function home(Request $request)
    {
        $data = [];
        $data['version'] = '0.1.2';

        // An example of how to use session on laravel.
        $last_updated = $request->session()->has('last_updated') ? 
            $request->session()->pull('last_updated') : 
            'none';
        $data['last_updated'] = $last_updated;

        return view('contents/home', $data);
    }

    public function upload(Request $request)
    {
        $data = [];
        
        if($request->isMethod('post')) {
            $this->validate(
                $request,
                [
                    'image_upload' => 'mimes:jpeg, bmp, png'
                ]
            );

            // Move the uploaded file to images folder and renamed as attraction.jpg.
            // The uploaded file will replace the existing file on the folder with same name.
            Input::file('image_upload')->move('images', 'attractions.jpg');

            return redirect('/');
        }

        return view('contents/upload', $data);
    }
}
