<?php

if (!function_exists('get_api_error')){
	function get_api_error($status_code=NULL,$status_message=NULL) {
		if($status_message==NULL){
			switch($status_code){
				case '400':
					$message = 'Some informations missing. Please try again later.';
				break;
				case '401':
					$message = 'Something went wrong. Please try again later.';
				break;
				case '402':
					$message = 'Authentication Failure.';
				break;
				case '403':
					$message = 'Authentication Missing.';
				break;
				case '404':
					$message = 'Unknown Method.';
				break;
				case '405':
					$message = 'Account modified, Login again.';
				break;
				case '406':
					$message = 'Account was used in another device.';
				break;
				case '407':
					$message = 'Server couldnot process this request.';
				break;
				
			}
		}else{
			$message = $status_message;
		}
		return $message;
	}
}

