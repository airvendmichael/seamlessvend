<?php

if($type <= 4 || $type == 27){
	$dest_msisdn = $data['details']['account'];
	$account = $dest_msisdn;
	$network_id = $data['details']['networkid'];
	$amount = $data['details']['amount'];
	$ref = $data['details']['ref'];


		
} 


if($type==10) {
	$destination = $data['details']['account'];
	$customername = $data['details']['customername'];
	$amount      = $data['details']['amount'];
	$ref         = $data['details']['ref'];
	$type        = $data['details']['type'];
	$contacttype = $data['details']['contacttype'];
	$customernumber = $data['details']['customernumber'];
	$customeraddress = $data['details']['customeraddress'];
	$customerphone = $data['details']['customerphone'];
	$customeremail = $data['details']['customeremail'];
	$account = $destination;
	
}
		//Ikeja Prepaid
if($type==11) {		

	$destination = $data['details']['account'];
	$customername = $data['details']['customername'];
	$amount      = $data['details']['amount'];
	$ref         = $data['details']['ref'];
	$type        = $data['details']['type'];
	$contacttype = $data['details']['contacttype'];
	$customernumber = $data['details']['customernumber'];
	$customeraddress = $data['details']['customeraddress'];
	$customerphone = $data['details']['customerphone'];
	$customeremail = $data['details']['customeremail'];
	$account = $destination;
}

//Ibadan Prepaid  or Postpaid
if($type==12 || $type==23 ){

	$destination = $data['details']['account'];
	$customername = $data['details']['customername'];
	$amount      = $data['details']['amount'];
	$ref         = $data['details']['ref'];
	$type        = $data['details']['type'];
	$contacttype = $data['details']['contacttype'];
	$customernumber = $data['details']['customernumber'];
	$customeraddress = $data['details']['customeraddress'];
	$customerphone = $data['details']['customerphone'];
	$customeremail = $data['details']['customeremail'];
	$account = $destination;
}


if($type==13) {
	
//	echo '<pre>';this is Eko prepaid
//	print_r($vendData);
//	echo '</pre>'
		$destination = $data['details']['account'];
	$customername = $data['details']['customername'];
	$amount      = $data['details']['amount'];
	$ref         = $data['details']['ref'];
	$type        = $data['details']['type'];
	$contacttype = $data['details']['contacttype'];
	$customernumber = $data['details']['customernumber'];
	$customeraddress = $data['details']['customeraddress'];
	$customernumber = $data['details']['customerphone'];
	$customeremail = $data['details']['customeremail'];
	$account = $destination;

} 
if ($type == 14) {
	//eko postpaid
	$destination = $data['details']['account'];
	$customername = $data['details']['customername'];
	$amount      = $data['details']['amount'];
	$ref         = $data['details']['ref'];
	$type        = $data['details']['type'];
	$contacttype = $data['details']['contacttype'];
	$customernumber = $data['details']['customernumber'];
	$customeraddress = $data['details']['customeraddress'];
	$customerphone = $data['details']['customerphone'];
	$customeremail = $data['details']['customeremail'];
	$account = $destination;
  

    }

//PHEDC POSTPAID
    if ($type == 15) {
	
	$destination = $data['details']['account'];
	$customername = $data['details']['customername'];
	$amount      = $data['details']['amount'];
	$ref         = $data['details']['ref'];
	$type        = $data['details']['type'];
	$contacttype = $data['details']['contacttype'];
	$customernumber = $data['details']['customernumber'];
	$customeraddress = $data['details']['customeraddress'];
	$customerphone = $data['details']['customerphone'];
	$customeremail = $data['details']['customeremail'];
	$account = $destination;
  

    }
//PHEDC PREPAID

    if ($type == 16) {
	$destination = $data['details']['account'];
	$customername = $data['details']['customername'];
	$amount      = $data['details']['amount'];
	$ref         = $data['details']['ref'];
	$type        = $data['details']['type'];
	$contacttype = $data['details']['contacttype'];
	$customernumber = $data['details']['customernumber'];
	$customeraddress = $data['details']['customeraddress'];
	$customerphone = $data['details']['customerphone'];
	$customeremail = $data['details']['customeremail'];
	$account = $destination;
  

    }

//KEDCO

if($type==20) {
	$destination = $data['details']['account'];
	$customername = $data['details']['customername'];
	$amount      = $data['details']['amount'];
	$ref         = $data['details']['ref'];
	$type        = $data['details']['type'];
	$contacttype = $data['details']['contacttype'];
	$customernumber = $data['details']['customernumber'];
	$customeraddress = $data['details']['customeraddress'];
	$customerphone = $data['details']['customerphone'];
	$customeremail = $data['details']['customeremail'];
	$account = $destination;
	
}

//Enugu Prepaid
	
if($type==21) {
	
	$destination = $data['details']['account'];
	$customername = $data['details']['customername'];
	$amount      = $data['details']['amount'];
	$ref         = $data['details']['ref'];
	$type        = $data['details']['type'];
	$contacttype = $data['details']['contacttype'];
	$customernumber = $data['details']['customernumber'];
	$customeraddress = $data['details']['customeraddress'];
	$customerphone = $data['details']['customerphone'];
	$customeremail = $data['details']['customeremail'];
	$account = $destination;

} 

//EEDC Post paid
if($type==22) {
	
//	echo '<pre>';
//enugu; 
//	echo '</pre>';
		$destination = $data['details']['account'];
	$customername = $data['details']['customername'];
	$amount      = $data['details']['amount'];
	$ref         = $data['details']['ref'];
	$type        = $data['details']['type'];
	$contacttype = $data['details']['contacttype'];
	$customernumber = $data['details']['customernumber'];
	$customeraddress = $data['details']['customeraddress'];
	$customerphone = $data['details']['customerphone'];
	$customeremail = $data['details']['customeremail'];
	$account = $destination;
	
} 

//Abuja Prepaid
if($type == 24){

	$destination = $data['details']['account'];
	$customername = $data['details']['customername'];
	$amount      = $data['details']['amount'];
	$ref         = $data['details']['ref'];
	$type        = $data['details']['type'];
	$contacttype = $data['details']['contacttype'];
	$customernumber = $data['details']['customernumber'];
	$customeraddress = $data['details']['customeraddress'];
	$customernumber = $data['details']['customerphone'];
	$customeremail = $data['details']['customeremail'];
	$account = $destination;

	}

if($type == 25){

	$destination = $data['details']['account'];
	$customername = $data['details']['customername'];
	$amount      = $data['details']['amount'];
	$ref         = $data['details']['ref'];
	$type        = $data['details']['type'];
	$contacttype = $data['details']['contacttype'];
	$customernumber = $data['details']['customernumber'];
	$customeraddress = $data['details']['customeraddress'];
	$customernumber = $data['details']['customerphone'];
	$customeremail = $data['details']['customeremail'];
	$account = $destination;

	}

//DSTV

	if($type == 30){
	$smartcard = $data['details']['account'];
	$account = $smartcard;
	$customername = $data['details']['customername'];
	$amount      = $data['details']['amount'];
	$ref         = $data['details']['ref'];
	$customernumber = $data['details']['customernumber'];
	$period      = $data['details']['invoicePeriod'];
	$type   	= $data['details']['type'];
}

//GOTV

if($type == 40){


	$smartcard = $data['details']['account'];
	$account = $smartcard;
	$customername = $data['details']['customername'];
	$amount      = $data['details']['amount'];
	$ref         = $data['details']['ref'];
	$customernumber = $data['details']['customernumber'];
	$period      = $data['details']['invoicePeriod'];
	$type   	= $data['details']['type'];

		}
//STARTIMES
if($type == 70){
	
$smartcard     = $data['details']['account'];
$account = $smartcard;
$amount      = $data['details']['amount'];
$ref         = $data['details']['ref'];
}

//SPECTRANET
if($type == 90){

	$pinvalue = $data['details']['pinvalue'];
    $pincount = $data['details']['pincount'];
    $ref         = $data['details']['ref'];
    $amount      = $data['details']['amount'];
                    }
                          

//WAEC
if($type == 80){
	$numberOfPins = $data['details']['pincount'];
	$pinValue     = $data['details']['pinvalue'];
	$amount       = $data['details']['amount'];
	$ref          = $data['details']['ref'];
	
				
}

if($type == 81){
	$account = $data['details']['account'];
	$code     = $data['details']['code'];
	$amount       = $data['details']['amount'];
	$ref          = $data['details']['ref'];
	$destination = $account;
				
}

//SMILE RECHARGE
if($type == 50){
	$account     = $data['details']['account'];
	$destination = $account;
	$amount      = $data['details']['amount'];
	$ref         = $data['details']['ref'];
}

//SMILE BUNDLE
if($type == 60){

	$account     = $data['details']['account'];
	$amount      = $data['details']['amount'];
	$ref         = $data['details']['ref'];
	$bundle	  = $data['details']['code'];
	$qty	     = $data['details']['pincount'];
}

if ($type == "100") {
	//eko postpaid
	$destination = $data['details']['account'];
	$customername = $data['details']['customername'];
	$amount      = $data['details']['amount'];
	$ref         = $data['details']['ref'];
	$type        = $data['details']['type'];
	$contacttype = $data['details']['contacttype'];
	$customernumber = $data['details']['customernumber'];
	$customeraddress = $data['details']['customeraddress'];
	$customerphone = $data['details']['customerphone'];
	$customeremail = $data['details']['customeremail'];
	$account = $destination;
  
}
