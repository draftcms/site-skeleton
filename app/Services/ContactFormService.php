<?php

namespace App\Services;

use App\Models\ContactForms;
use App\Models\ContactFormTypes;
use App\Models\ContactFormResponses;
use Auth;
use Mail;
use Illuminate\Http\Request;

class ContactFormService {

	public static function handleFormData($request, $input){

		/* set message default message status */
		$status = "Your message was successfully sent.";
		$status_type = "status";
		$redirect = redirect('contact');

		/* attempt adding entry to ContactForm */
		try {

			/* create ContactForm entry */
            ContactForms::create([
                'name'       	=> $input['name'],
                'email'      	=> $input['email'],
                'type_id'   	=> $input['type_id'],
                'notes'         => $input['notes'],
            ]);

		} catch (\Exception $e) {
			$status = 'Your message could not be sent.';
			$status_type = 'fail';
			$redirect = redirect('contact')->withInput();
		}


		/* attempt adding entry to ContactFormResponse */
		try {

			$user_id = null;
			
			if(Auth::user()){
				$user_id = Auth::user()->id;
			}

			/* create ContactFormResponse entry */
            $response = ContactFormResponses::create([
                'name'       			=> $input['name'],
                'email'      			=> $input['email'],
                'type_id'   			=> $input['type_id'],
                'notes'         		=> $input['notes'],
                'ip_address'			=> $_SERVER['REMOTE_ADDR'],
                'user_id'				=> $user_id,
                'user_agent_string'		=> $_SERVER['HTTP_USER_AGENT'],
                'session'				=> json_encode($request->session()->all()),

            ]);

            /* build data array for email */
			$data = [
				'name' 			=> $response->name,
				'email'			=> $response->email,
				'notes'			=> $response->notes,
				'ip_address'	=> $response->ip_address,
				'user_id'		=> $response->user_id,
				'user_agent'	=> $response->user_agent_string,
				'session'		=> $response->session,
			];

			/* send out email to recipients associated with type_id (see ContactFormTypes table) */
			Mail::send('emails.contact', $data, function ($message) use ($response){
	    		$message->from($response->email, 'Draft-CMS E-mail Service');
	    		$message->to(explode(',', $response->type->recipients))->subject($response->type->friendly_name);
			});

		} catch (\Exception $e) {
			$status = 'Your message could not be sent.';
			$status_type = 'fail';
			$redirect = redirect('contact')->withInput();
		}

		/* flash status message to let user know what happened */
		$request->session()->flash($status_type, $status);

		return $redirect;
	}

}