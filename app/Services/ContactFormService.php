<?php

namespace App\Services;

use App\Models\ContactForms;
use App\Models\ContactFormTypes;
use App\Models\ContactFormResponses;
use Auth;
use Illuminate\Http\Request;

class ContactFormService {

	public static function handleFormData($request, $input){

		// add entry to ContactForm
		try {

			/* create ContactForm entry */
            ContactForms::create([
                'name'       		=> $input['name'],
                'email'      		=> $input['email'],
                'type_id'   		=> $input['type_id'],
                'notes'         	=> $input['notes'],
            ]);

		} catch (\Exception $e) {
			dd($e);
		}


		// add entry to ContactFormResponse
		try {

			$user_id = null;
			
			if(Auth::user()){
				$user_id = Auth::user()->id;
			}

			/* create ContactFormResponse entry */
            ContactFormResponses::create([
                'name'       		=> $input['name'],
                'email'      		=> $input['email'],
                'type_id'   		=> $input['type_id'],
                'notes'         	=> $input['notes'],
                'ip_address'			=> $_SERVER['REMOTE_ADDR'],
                'user_id'				=> $user_id,
                'user_agent_string'		=> $_SERVER['HTTP_USER_AGENT'],

            ]);

		} catch (\Exception $e) {
			
		}
	}

}