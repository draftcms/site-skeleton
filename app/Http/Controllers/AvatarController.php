<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Services\AvatarService;
use Image;
use Illuminate\Support\Facades\Input;
use Auth;

class AvatarController extends Controller
{

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        /* create instance of image from user */
        $input_img = Input::file('file');
        $img = Image::make($input_img);
        $orig_name = $input_img->getClientOriginalName();

        AvatarService::addUserAvatar($request, Auth::user(), $img, $orig_name);

        return redirect('profile');
    }

}
