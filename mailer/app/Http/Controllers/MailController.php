<?php

namespace App\Http\Controllers;

use Mail;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public function mail_encode($email) {

    	try {
		    Mail::send('emails.encode', [], function($message) use ($email){
		    		$message->to($email)->subject('Vidéo encodé');
		    		$message->from('puig_j@etna-alternance.net', 'myYoutube');
		    	});
		    return response()->json("Successfully sent email", 200);
		} catch (Exception $ex) {
		    return response()->json("We've got errors!", 400);
		}
    }

    public function mail_password($email) {

    	try {
		    Mail::send('emails.password', [], function($message) use ($email){
		    		$message->to($email)->subject('Changement de mot de passe');
		    		$message->from('puig_j@etna-alternance.net', 'myYoutube');
		    	});
		    return response()->json("Successfully sent email", 200);
		} catch (Exception $ex) {
		    return response()->json("We've got errors!", 400);
		}
    }
}
