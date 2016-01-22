<?php
	function getCurl(){
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "<URL>");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_HTTPHEADER,array(
		  "Content-Type: application/x-www-form-urlencoded",
		  "X-Access-ID: <Key>",
		  "X-Secret-ID: <Key>"
		));
		curl_setopt($ch, CURLOPT_POST, true);
		
		return $ch;
	}

	function getUserData($website, $user, $from = "0000-00-00 00:00:00", $to = "9999-99-99 23-59-59"){		
		$ch = getCurl();

		

		if(!empty($from)&& !empty($to))
		{
			$params = "website={$website}&user={$user}";
		
		}
		else
		{
			$params = "website={$website}&user={$user}&from={$from}&to={$to}";
		}
			
		
		
		//print_r($params);
		
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		
		$response = curl_exec($ch);
		curl_close($ch);

		return json_decode($response);
		}
	
	
	//Testing code
	print_r(getUserData(2, "b6e85486dc9ddccf"));
	//End of testing code
?>