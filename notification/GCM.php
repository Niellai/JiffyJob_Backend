<?php
class GCM{
	function constructor(){
	
	}
	
	//send message to single user by gcm registration id
	public function send($to, $message){
		$message = array('message'=>$message);
		 $fields = array(
	            'to' => $to,
	            'data' => $message,
	        );
		return $this->sendPushNotification($fields);
	}
	
	//send message to a topic by topic id
	public function sendToTopic($to, $message){	
		$message = array('message'=>$message);
		$fields = array(
	            'to' => '/topics/' . $to,
	            'data' => $message,
        	);
		return $this->sendPushNotification($fields);
	}

	//send message to multiple user by gcm registration ids
	public function sendMultiple($registration_ids, $message) {
		$message = array('message'=>$message);
		$registration_ids = explode(",",$registration_ids);
		
	        $fields = array(
	            'registration_ids' => $registration_ids,
	            'data' => $message,
	        );
	 
	        return $this->sendPushNotification($fields);
	}

	public function sendPushNotification($fields){	
		require '../config.php';
		//echo "server key: ".$JJServerKey."<br>";
		
		//GCM post url
		$url = 'https://gcm-http.googleapis.com/gcm/send';
		$headers = array(
	            'Authorization: key=' . $JJServerKey,
        	    'Content-Type: application/json'
	        );
		
		// Open connection
	        $ch = curl_init();
	 	
		// Set the url, number of POST vars, POST data
	        curl_setopt($ch, CURLOPT_URL, $url);
	 
	        curl_setopt($ch, CURLOPT_POST, true);
	        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	 
	        // Disabling SSL Certificate support temporarly
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		//input all the fields
		$encodeJson = json_encode($fields);
		echo "encodeJson: ".$encodeJson."<br>";
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		 
	        // Execute post
	        $result = curl_exec($ch);
	        if ($result === FALSE) {
	            die('Curl failed: ' . curl_error($ch));
	        }
	 
	        // Close connection
	        curl_close($ch);
	 		
	        return $result;		
	}
}
?>