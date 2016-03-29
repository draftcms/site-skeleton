<?php

namespace App\Http\Controllers;

use App\Models\ContactFormTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Services\ContactFormService;

class ContactFormController extends Controller
{
    /**
     * Get view for contact form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getForm(Request $request)
    {

    	$types = ContactFormTypes::all();

        return view('contact.form')->with('types', $types);
    }

    /**
     * Post form and send out email, show success/failure to user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postForm(Request $request)
    {

    	ContactFormService::handleFormData($request, Input::all());

        //return redirect('home');
    }

}
