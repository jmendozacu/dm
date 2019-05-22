<?php 
	
	if ($_POST["input_name"] == "")
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Name fields are empty!'));
		die($output);
	}
	elseif ($_POST["input_email"] == "")
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Email fields are empty!'));
		die($output);
	}
	elseif ($_POST["input_events"] == "")
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Events fields are empty!'));
		die($output);
	}
	else
	{
		$email_to 			=   'some@email.com'; 
		$contact_name     	=   $_POST['input_name'];  
		$contact_email    	=   $_POST['input_email'];
    	$contact_events 	=   $_POST['input_events'];	
		$contact_events_string = implode(", ", $contact_events);
		$contact_message  	=   $_POST['input_message'];
	
    	$headers  	= "From: ".$contact_email."\r\n";	
		$headers   .= "Reply-To: ".$contact_email."\r\n";	
		$subject 	= "RSVP message from Mr/Mrs ".$contact_name;		
		
		$finalmessage = "$contact_message \n\n";
		$finalmessage.= "I will attend to: ". $contact_events_string;

    	if(mail($email_to, $subject, $finalmessage, $headers)){
        	$output = json_encode(array('type'=>'success', 'text' => 'Message Sent'));
    	}else{
        	$output = json_encode(array('type'=>'error', 'text' => 'Failed'));
   		}		
		die($output);
	}
?>