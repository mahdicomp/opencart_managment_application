<?php
/*
  $Id: fcm.php $
   opncart Open Source Shopping Cart Solutions
  http://www.opencart-ir.com
  version:2.8
*/
  

 final class Fcm {
     
 
    function send_notification($notification_text,$notification_title,$tokens=array(),$serverKey,$https) {
		
      $url = "https://fcm.googleapis.com/fcm/send";
       
      $data = array(
        "registration_ids" => $tokens,            // for multiple devices 
        "notification" => array( 
            "title" => $notification_title, 
            "body" =>$notification_text,
            "message"=>$notification_text,
            "click_action"=>"FCM_PLUGIN_ACTIVITY",
            'icon'=>'https://image.flaticon.com/sprites/authors/smashicons.png'
        ),
       'priority' => 'high',
        "data"=>array(
        "name"=>"xyz",
        'image'=>'https://image.flaticon.com/sprites/authors/smashicons.png'
        ) 
    ); 

    $json = json_encode($data); 
 
    $headers = array();
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Authorization: key='. $serverKey;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
    //Send the request
    $response = curl_exec($ch);
     // var_dump($response);
     $cur_message=json_decode($response,true);
    
     return($cur_message['success']);
      
      
    if ($response === FALSE) {
    die('FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
		
	
	
	}
	

   
	
  }
  
?>
