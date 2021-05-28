<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

if($type <= 4 || $type == 27){
	//include '/var/www/vhosts/api/airtel/ussd/processor/'. $net['airtime_processor'];
	if(!empty($account) && !empty($networkid) && !empty($amount)){
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
		$token = "93848384838478347";
		$unit = "35.56unit";
		$transref = "udodu-guriekfu-5948594";
	}
	else {
		$result = '99999999';
		
		}
}


		//Ikeja Prepaid
if($type==11) {		

	$transctionid = "CPL-".$transaction_id;
	require "/var/www/vhosts/api/vas/electricity/itex.php";
	//$output =api_call($requestIEvend, "http://vas.itexapp.com/vas/ie/purchase");
	$output =api_call($requestIEvend, "http://197.253.19.75:8029/vas/ie/purchase");
	$output = json_decode($output, true);
	$response = $output;
	
	if($output['status'] == 1){
		$result = 0;
		$token = $output['token'];
		$unit = $output['unit_value'].$output['unit'];
		$transref = $output["transactionUniqueNumber"];
	}
	else {
		$result = '99999999';
		
		}
}


//Ibadan Pretpaid
if($type==12){

       require '/var/www/vhosts/api/vas/electricity/fet.php';

			$ret = callibedc($amount,$destination,$transaction_id, 2);
			$output = $ret;
			$vxdata = $ret;
			$response = $output;

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

if($type == 23) {
	$transctionid = "CPL-".$transaction_id;
	require "/var/www/vhosts/api/vas/electricity/itex.php";
	//$output =api_call($requestIEvend, "http://vas.itexapp.com/vas/ie/purchase");
	$output =api_call($requestIBvend, "http://197.253.19.75:8029/vas/ibedc/payment");;
	$output = json_decode($output, true);
	$response = $output;
	if($output['status'] == 1){
		$result = 0;
		$token = $output['token'];
		$unit = $output['unit_value'].$output['unit'];
		$transref = $output["transactionUniqueNumber"];
	}
	else {
		$result = '99999999';
		
		}
}



if($type==13) {
	
//	echo '<pre>';this is Eko prepaid
//	print_r($vendData);
//	echo '</pre>'

				require '/var/www/vhosts/api/vas/electricity/req_abuja_electric.php';

		$ctype = "Pre Paid";
	
	

	$output = vend_eko($destination, $amount, $customername, $customerphone, $ctype);
	$response = $output;

	if($output['responseCode'] == 0){
    
    $vendData = $output;
    $result = 0;
}
else {
	$result = '99999999';
	$error  = $output['responseCode']. '|' . $output['message'];		
	}
	
}
if ($type == 14) {
	//eko postpaid

require '/var/www/vhosts/api/vas/electricity/req_abuja_electric.php';
	

		$ctype = "Post Paid";

	$output = vend_eko($destination, $amount, $customername, $customerphone, $ctype);
	$response = $output;
	if($output['responseCode'] == 0){
    
    $vendData = $output;
    $result = 0;
}
else {
	$result = '99999999';
	$error  = $output['responseCode']. '|' . $output['message'];		
}

    }


//PHEDC

    if($type==15 || $type==16){
	$payment = $amount * 100;
	$transactionid = "CPL-".$transaction_id;
	$account = $destination;
	$customerphone = "09032878128";
	$customername = "Michael";
	require "/var/www/vhosts/api/vas/electricity/itex.php";
	$verify = api_call($requestPHverify, "http://197.253.19.75:8029/vas/phed/validation");
	$verify = json_decode($verify, true);
	$customernumber = $verify['productCode'];
	$requestPHvend = array("terminalId"=>WALLETID,
					"wallet" =>WALLETID,
					"username"=>USERNAME,
					"password"=>PASSWORD,
					"account"=>$account,
					"phone"=>$customerphone,
					"amount"=>$payment,
					"pin"=>pin(),
					"type"=>$t,
					"channel"=>CHANNEL,
					"productCode"=>$customernumber,
					"customerName"=>$customername,
					"paymentMethod"=>"cash",
					"clientReference"=>$transactionid);
	$input = $requestPHvend;
	$output =api_call($input, "http://197.253.19.75:8029/vas/phed/payment");
	$output = json_decode($output, true);
	$response = $output;
	//print_r($output);
	//exit();
	if($output['error'] == false || $output['status'] == 1){
		$result = 0;
		$token = $output['token'];
		$unit = $output['unit_value'];
		$transref = $output["externalReference"];
	}
	else {
	$result = '99999999';
	$error  = $output['responseCode']. '|' . $output['message'];		
	}
}


//KEDCO

if($type==20){

       require '/var/www/vhosts/api/vas/electricity/fet.php';

			$ret = callkedco($amount,$destination,$transaction_id,2,$customernumber);
			$output = $ret;
			$vxdata = $ret;
			$response = $output;
			print_r($ret);
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


//Enugu Prepaid
	
if($type==21) {
    $presentProvider = getPresentProvider($type, $mysqli);
    $pProvider = $presentProvider[0]['provider_id'];
    if($pProvider == 4){
    require '/var/www/vhosts/api/vas/electricity/fet.php';
	if(empty($customerphone)&& strlen($customerphone) < 2){
		$customerphone = "09032878128";
		}
		if($type == 21){
			$ret = callenugudc($amount,$destination,$transaction_id, 2,$customerphone,'PRE',$customernumber);
			$output = $ret;
			$response = $ret;
		}
		if(is_array($ret)){
			$vxdata = $ret;
			if(isset($ret['success'])){
				if($ret['success']== true){
					$result = '0';
					if(isset($ret['creditToken'])){
						$token = $ret['creditToken'];
					}
				}
				else {
		$result = '99999999';
		}
			}
			
		}

    }
    if($pProvider == 5){
        include '/var/www/vhosts/api/vas/electricity/shagoapi.php';
        
        if($type == 21){$meterType = "PREPAID";}
        if($type == 22){$meterType = "POSTPAID";}
        $content = array("request_id"=>$transaction_id,"amount"=>$amount,"address"=>$customeraddress, 
        "name"=>$customername,"serviceCode"=>"AOB","disco"=>"EEDC","meterNo"=>$account,"type"=>$meterType);
        $output = shagoApi($content);
        $response = $output;
        $resp = json_decode($output, true);
        if($resp['status']== 200){
            $result = '0';
            $token = $resp['token'];
            $unit = $resp['unit'];
            $transref = $resp['transId'];
        }else{
            $return = '99999999';
        }
    }

} 

//EEDC Post paid
if($type==22) {
    $presentProvider = getPresentProvider($type, $mysqli);
    $pProvider = $presentProvider[0]['provider_id'];
    if($pProvider == 4){
	require '/var/www/vhosts/api/vas/electricity/fet.php';
	if(empty($customerphone)&& strlen($customerphone) < 2){
		
		$customerphone = "09032878128";
		}
		
		if($type == 22){
			$ret = callenugudc($amount,$destination,$transaction_id, 2,$customerphone,'POST',$customernumber);
			$output = $ret;
			$response = $ret;
		}
		
		if(is_array($ret)){
			$vxdata = $ret;
			if(isset($ret['success'])){
				if($ret['success']== true){
					$result = '0';
					if(isset($ret['creditToken'])){
						$token = $ret['creditToken'];
					}
				}
				else {
		$result = '99999999';
		
		}
			}
			
		}
    }
    if($pProvider == 5){
        include '/var/www/vhosts/api/vas/electricity/shagoapi.php';
        
        if($type == 21){$meterType = "PREPAID";}
        if($type == 22){$meterType = "POSTPAID";}
        $content = array("request_id"=>$transaction_id,"amount"=>$amount,"address"=>$customeraddress, 
        "name"=>$customername,"serviceCode"=>"AOB","disco"=>"EEDC","meterNo"=>$account,"type"=>$meterType);
        $output = shagoApi($content);
        $response = $output;
        $resp = json_decode($output, true);
        if($resp['status']== 200){
            $result = '0';
            $token = $resp['token'];
            $unit = $resp['unit'];
            $transref = $resp['transId'];
        }else{
            $return = '99999999';
        }
    }
	

} 



//Abuja Prepaid
if($type == 24){
	$presentProvider = getPresentProvider($type, $mysqli);
    $pProvider = $presentProvider[0]['provider_id'];
	if($pProvider == 3){
		require '/var/www/vhosts/api/vas/electricity/req_abuja_electric.php';
		$output = vend_abuja($destination, $amount, $customername, $customerphone);
		$response =$output;
		if($output['responseCode'] === 0){
		
			$vendData = $output;
			$message =  $vendData['details']['errorCode'];
			$token = $vendData['confirmationCode'];
			$unit = $vendData['unit'];
			$transref= $vendData['transactionId'];

			$result = 0;
		}
		else {
			$result = '99999999';
			$error  = $output['responseCode'] . '|' . $output['message'];		
		}
	}

	if($pProvider == 2){
					require "/var/www/vhosts/api/vas/electricity/itex.php";
		$verify = api_call($requestABverify, "http://197.253.19.75:8029/vas/abuja/validation");
		$verify = json_decode($verify, true);
		$customernumber = $verify['productCode'];

		$requestABvend = array("wallet"=>WALLETID,
						"username"=>USERNAME,
						"password"=>PASSWORD,
						"requestType"=>"1",
						"meterType"=>$meterType,
						"meterNo"=>$account,
						"phone"=>"09032878128",
						"channel"=>CHANNEL,
						"amount"=>$payment,
						"pin"=>pin(),
						"productCode"=>$customernumber,
						"paymentMethod"=>"cash",
						"clientReference"=>$transactionid);
		$input = $requestABvend;
		$output =api_call($input, "http://197.253.19.75:8029/vas/abuja/payment");
		$output = json_decode($output, true);
		if($output['status'] == 1){
			$result = 0;
			$token = $output['token'];
			$unit = $output['units'];
			$transref = $output["transactionUniqueNumber"];
			$transref = $output["externalReference"];
		}
		else{
			$result = '99999999';
		}
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
