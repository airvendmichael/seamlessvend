<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

if($type <= 4 || $type == 27){
	//include '/var/www/vhosts/api/airtel/ussd/processor/'. $net['airtime_processor'];
	if(!empty($account) && !empty($network_id) && !empty($amount)){
		$result = 0;
		$ep['senum'] = "23453028394792837";
        $ep['scnum'] = "45957483848";
        $ep['pin']   = '';
        $ep['expires'] = "";
	}
	if(strlen($result) == 0) {
	$result = 99999999;
}

// Catch unset product type (airtime/Data)

		
} 



if($type == 10) {
	$transactionid = "CPL-".$transaction_id;
	
	$output = ["status"=>1];
	
	$response = $output;
	if($output['status'] == 1){
		$result = 0;
		// $token = "93848384838478347";
		// $unit = "35.56unit";
		// $transref = "udodu-guriekfu-5948594";
	}
	else {
		$result = '99999999';
		
		}
}


		//Ikeja Prepaid
if($type==11) {		

	$transactionid = "CPL-".$transaction_id;
	
	$output = ["status"=>1];
	
	$response = $output;
	if($output['status'] == 1){
		$result = 0;
		$token = "93848384838478347";
		$unit = "35.56unit";
		$transref = "udodu-guriekfu-5948594";
	}
	else {
		$result = '99999999';
	}
		
}


//Ibadan Pretpaid
if($type==12){

	$transactionid = "CPL-".$transaction_id;
	
	$output = ["status"=>1];
	
	$response = $output;
	if($output['status'] == 1){
		$result = 0;
		$token = "93848384838478347";
		$unit = "35.56unit";
		$transref = "udodu-guriekfu-5948594";
	}
	else {
		$result = '99999999';
	}
}

if($type == 23) {
	$transactionid = "CPL-".$transaction_id;
	
	$output = ["status"=>1];
	
	$response = $output;
	if($output['status'] == 1){
		$result = 0;
		// $token = "93848384838478347";
		// $unit = "35.56unit";
		// $transref = "udodu-guriekfu-5948594";
	}
	else {
		$result = '99999999';
	}
		
}



if($type==13) {
	
//	echo '<pre>';this is Eko prepaid
//	print_r($vendData);
//	echo '</pre>'

	$transactionid = "CPL-".$transaction_id;
		
	$output = ["status"=>1];

	$response = $output;
	if($output['status'] == 1){
		$result = 0;
		$token = "93848384838478347";
		$unit = "35.56unit";
		$transref = "udodu-guriekfu-5948594";
	}
	else {
		$result = '99999999';
				
	}
	
}
if ($type == 14) {
	//eko postpaid
	
$transactionid = "CPL-".$transaction_id;
	
	$output = ["status"=>1];
	
	$response = $output;
	if($output['status'] == 1){
		$result = 0;
		// $token = "93848384838478347";
		// $unit = "35.56unit";
		// $transref = "udodu-guriekfu-5948594";
	}
	else {
		$result = '99999999';
				
}

    }


//PHEDC

    if($type==15 || $type==16){
		$transactionid = "CPL-".$transaction_id;
	
		$output = ["status"=>1];
		
		$response = $output;
		if($output['status'] == 1){
			$result = 0;
			$token = "93848384838478347";
			$unit = "35.56unit";
			$transref = "udodu-guriekfu-5948594";
		}
		else {
			$result = '99999999';
					
	}
}


//KEDCO

// if($type==20){

//        require '/var/www/vhosts/api/vas/electricity/fet.php';

// 			$ret = callkedco($amount,$destination,$transaction_id,2,$customernumber);
// 			$output = $ret;
// 			$vxdata = $ret;
// 			$response = $output;
// 			print_r($ret);
// 			if(isset($ret['success'])){
// 				if($ret['success']== true){
// 					$result = '0';
// 					if(isset($ret['creditToken'])){
// 						$token = $ret['creditToken'];
// 					}
// 				}
// 			}
// 			else {
// 	$result = '99999999';
// 	$error  = $json_data['code'] . '|' . $json_data['message'];		
// }
// }


//Enugu Prepaid
	
if($type==21) {
    $transactionid = "CPL-".$transaction_id;
	
	$output = ["status"=>1];
	
	$response = $output;
	if($output['status'] == 1){
		$result = 0;
		$token = "93848384838478347";
		$unit = "35.56unit";
		$transref = "udodu-guriekfu-5948594";
	}
	else {
		$result = '99999999';
	}
   
} 

if($type==22) {
    $transactionid = "CPL-".$transaction_id;
	
	$output = ["status"=>1];
	
	$response = $output;
	if($output['status'] == 1){
		$result = 0;
		// $token = "93848384838478347";
		// $unit = "35.56unit";
		// $transref = "udodu-guriekfu-5948594";
	}
	else {
		$result = '99999999';
	}
   
} 




//Abuja Prepaid
if($type == 24){
		$transactionid = "CPL-".$transaction_id;
		
		$output = ["status"=>1];
		
		$response = $output;
		if($output['status'] == 1){
			$result = 0;
			$token = "93848384838478347";
			$unit = "35.56unit";
			$transref = "udodu-guriekfu-5948594";
		}
		else {
			$result = '99999999';
		}
	   

}


//DSTV

	if($type == 30){
	require '/var/www/vhosts/api/vas/airVend/baxi/req_dstv.php';
	$json_data   = json_decode($output,true);
	$response = $output;
	if($json_data['details']['status'] == 'ACCEPTED') {	
	$result = '0';
	
} else {
	$result = '99999999';
	$error  = $json_data['code'] . '|' . $json_data['message'];		
}

}

//GOTV

if($type == 40){
	require '/var/www/vhosts/api/vas/airVend/baxi/req_gotv.php';
	$json_data   = json_decode($output,true);
	$response = $output;
	if($json_data['details']['status'] == 'ACCEPTED') {	
	$result = '0';
	
	} else {
	$result = '99999999';
	$error  = $json_data['code'] . '|' . $json_data['message'];		
	}

		}


//STARTIMES
if($type == 70){
	require '/var/www/vhosts/api/vas/airVend/baxi/req_startimes.php';
	$json_data   = json_decode($output,true);
	$response = $output;
		if($json_data['details']['status'] == 'ACCEPTED') {	
		$result = '0';
	
		} else {
			$result = '99999999';
		$error  = $json_data['details']['status'] . '|' . $json_data['details']['returnMessage'];		
		}

}


//SPECTRANET
if($type == 90){

	require '/var/www/vhosts/api/vas/airVend/baxi/req_spectranet.php';
	
                        $output = VendSpectranet($pincount, $amount, $pinvalue, $transaction_id);
                         $json_data = json_decode($output, true);
                         $response =$output;
	if ($json_data['details']['status'] == 'ACCEPTED') {
                            //if($json_data['details']['status'] == '0' ) {
                            $result = '0';
                        } else {
                            $result = '99999999';
                            $error = $json_data['code'] . '|' . $json_data['message'];
                        }

                    }
                          

//WAEC
if($type == 80){
	require '/var/www/vhosts/api/vas/airVend/baxi/req_waec.php';
	$response = $output;
	$json_data   = json_decode($output,true);
	if($json_data['details']['status'] == 'ACCEPTED') {	
	$result = '0';
	
	} else {
	$result = '99999999';
	$error  = $json_data['code'] . '|' . $json_data['message'];		
	}
				
}

//JAMB
if($type == 81){
	require '/var/www/vhosts/api/vas/electricity/req_abuja_electric.php';
	$output = vendJamb($account, $code, $amount, $transaction_id);
	$j   = json_decode($output,true);
	$response = $output;
	if($j['responseCode'] == 0){
    
    $result = 0;

	} else {
	$result = '99999999';
	$error  = $j['responseCode'] . '|' . $j['message'];		
	}

}

//SMILE RECHARGE
if($type == 50){
	require '/var/www/vhosts/api/vas/airVend/baxi/req_smile.php';
	$json_data   = json_decode($output,true);
	$response = $output;
						if($json_data['details']['status'] == 'ACCEPTED') {	
	$result = '0';
	
} else {
	$result = '99999999';
	$error  = $json_data['code'] . '|' . $json_data['message'];		
}

					
}


//SMILE BUNDLE
if($type == 60){

	require '/var/www/vhosts/api/vas/airVend/baxi/req_smilebundle.php';
	$response = $output;
	$json_data   = json_decode($output,true);					
	if($json_data['details']['status'] == 'ACCEPTED') {	
	$result = '0';
	
	} else {
	$result = '99999999';
		$error  = $json_data['code'] . '|' . $json_data['message'];		
	}
}

//BET9JA
if($type==100){

       require '/var/www/vhosts/api/vas/electricity/fet.php';

			$ret = callbet9ja($amount,$destination,$transaction_id,2);
			$output = $ret;
			$vxdata = $ret;
			$response = $output;
			print_r($ret);
			exit();
			if(isset($ret['success'])){
				if($ret['success']== true){
					$result = '0';
					if(isset($ret['creditToken'])){
						$token = $ret['creditToken'];
					}
				}
			}
			else {
	$result = '99999999';
	$error  = $json_data['code'] . '|' . $json_data['message'];		
}
}
