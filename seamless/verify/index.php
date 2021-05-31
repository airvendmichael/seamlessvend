<?php


error_reporting(~E_ALL);
ini_set('display_errors', 1); 

include '../function/db.inc.php';
include '../function/func.inc.php';
include '../function/json_func.php';


//The Server IP Address



$request_ip         = print_r($_SERVER['REMOTE_ADDR'],1);

//Treating the headers
$query_st = getallheaders();

$user = $query_st['username'];
$pass = $query_st['password'];
$hash = $query_st['hash'];


//Getting the content
$input= file_get_contents('php://input');
$data = json_decode($input, true);


//Verify Server Request Method
if($_SERVER['REQUEST_METHOD'] != "POST"){
	$response['status'] = 405;
	vend_response($response);
}

//Get If Vendor Exist now Includes IP Address.
//First Level Verification
$pass = get_Password($pass);


$vendor = getVendorAuth($user,$pass,$mysqli);

$date = date('Y-m-d');
if($vendor === FALSE) {
	$response['status'] = 401;
	$response['message'] = "Failed First Level Verification";

        $log_string = "\n\n" . $date .' AUTH FAILED: ' . $user  .  "\n\n";
        error_log($log_string,3,'vtu2_request.log');
       vend_response($response);
}

//Second Level Verification
// $ipaddress = ipaddress($vendor['linked_id'],$request_ip,$mysqli);
// if($ipaddress == FALSE){
// 	$response['status'] = 403;
// 	$response['message'] = "Failed Second Level Verification";
// 	vend_response($response);
// }

//Test Hashing with hashing Key

$test = $input.$vendor['hash_key'];
$hash = strtolower($hash);
$test = hash('sha512', $test);
$test = strtolower($test);
//$i = print_r($data,1);
//$log_string = "\n".$hash." \n".$test."\n";
//$log_string .=$vendor['hash_key']."\n";
//$log_string .= $i."\n";
error_log($log_string,3,'hash.log');
if($test != $hash){
$response['status'] = 401;
        $response['message'] = "Failed Third Level Verification";
        vend_response($response);
}
/*$test = $input.$vendor['hash_key'];
$test = hash('sha512', $test);
if($test != $hash){
$response['status'] = 401;
	$response['message'] = "Failed Third Level Verification";
	vend_response($response);
}*/

function dataplan($network_id, $mysqli){
$query = "SELECT Description, Amount, Code, Validity FROM dataplan WHERE network_id = $network_id";
$result = mysqli_query($mysqli, $query);
 while($res = mysqli_fetch_assoc($result)){
 	$array[]= array("description" => $res['Description'],
 					"Amount" => $res['Amount'],
 					"code" => $res['Code'],
 					"validity" => $res['Validity']);

 
 }
 return $array;
}

//IF DATA BUNDLE
$product_type = $data['details']['type'];
$account = $data['details']['account'];

$type = $product_type;
$destination = $account;
//Ikeja Postpaid

	if($product_type == 10){

		$presentProvider = getPresentProvider($type, $mysqli);

		$pProvider = $presentProvider[0]['provider_id'];
		if($pProvider == 2){

		require '/var/www/vhosts/api/vas/electricity/itex.php';
		$output = api_call($requestIEverify, "http://197.253.19.75:8029/vas/ie/validate");

		$output = json_decode($output, true);

		if(!empty($output['name'])){// && empty($output['MaxDemand']) ){
               $output = array( 'name'=> $output['name'], 'address'=>$output['address'], 'account'=>$destination,'status' =>0, "canVend"=>"1");


				$response['status'] = 200;
				$response['message'] = $output;
		}
		else{
				$response['status'] = 503;
				$response['message'] = "Verifying {$destination} not completed";
		}
	}

	if($pProvider == 1){
		require '/var/www/vhosts/api/vas/airVend/baxi/req_electricity_verify.php';

		$json_data   = json_decode($output,true);
		if(!empty($json_data['details'])  ){
				$output = $json_data['details'];
               $output = array( 'name'=> $output['name'], 'address'=>$output['address'], 'account'=>$destination,'status' =>0, "canVend"=>"1", "customernumber" =>$output["customerDtNumber"]);


				$response['status'] = 200;
				$response['message'] = $output;
		}
		else{
				$response['status'] = 503;
				$response['message'] = "Verifying {$destination} not completed";
		}
	}

	

		vend_response($response);

}

// IKeja Prepaid
if($product_type == 11){
	$presentProvider = getPresentProvider($type, $mysqli);

		$pProvider = $presentProvider[0]['provider_id'];
	if($pProvider == 2){
		
		require '/var/www/vhosts/api/vas/electricity/itex.php';
		$output = api_call($requestIEverify, "http://197.253.19.75:8029/vas/ie/validate");

		$output = json_decode($output, true);
		
		if(!empty($output['name']) ){
               $output = array( 'name'=> $output['name'], 'address'=>$output['address'], 'account'=>$destination,'status' =>0, "canVend"=>"1");


				$response['status'] = 200;
				$response['message'] = $output;
		}
		else{
				$response['status'] = 503;
				$response['message'] = "Verifying {$destination} not completed";
		}
	}

	if($pProvider == 1){
		require '/var/www/vhosts/api/vas/airVend/baxi/req_electricity_verify.php';

		$json_data   = json_decode($output,true);
		if(!empty($json_data['details'])  ){
				$output = $json_data['details'];
               $output = array( 'name'=> $output['name'], 'address'=>$output['address'], 'account'=>$destination,'status' =>0, "canVend"=>"1", "customernumber" =>$output["customerDtNumber"]);


				$response['status'] = 200;
				$response['message'] = $output;
		}
		else{
				$response['status'] = 503;
				$response['message'] = "Verifying {$destination} not completed";
		}
	}

		vend_response($response);

}
//Ibadan Prepaid
if($product_type == 12){
		
		 require '/var/www/vhosts/api/vas/electricity/fet.php';
                $fet = callibedc(0,$destination,0,1);


        if(is_array($fet) && $fet['responseCode'] == 0){
               $output = array( 'name'=> $fet['message'], 'address'=>' ','customernumber'=> $fet['customerId'],'account'=>$destination);

			$response['status'] = 200;
			$response['message'] = $output;
		}
		else{
			$response['status'] = 503;
			$response['message'] = "Verifying {$destination} not completed";
		}
		vend_response($response);

}

if($product_type == 23){
		
		 $account = $destination;
		require '/var/www/vhosts/api/vas/electricity/itex.php';
		$output = api_call($requestIBvend, "http://197.253.19.75:8029/vas/ibedc/validation");
		$output = json_decode($output, true);

        if($output['status'] == 1 ){
             $output = array( 'name'=> $output['name'], 'address'=>$output['address'], 'meterNumber'=>$destination,'status' =>0, "customernumber"=>$output['productCode']);

			$response['status'] = 200;
			$response['message'] = $output;
		}
		else{
			$response['status'] = 503;
			$response['message'] = "Verifying {$destination} not completed";
		}
		vend_response($response);

}


//Eko Prepaid
if($product_type == 13){

	require '/var/www/vhosts/api/vas/electricity/req_abuja_electric.php';
	$billerPublicId = "8E7485D9-1A67-4205-A49D-691E5B78C20D";
	$serviceName = "Pre Paid";
	
	$output = verifyDetails($destination,$billerPublicId, $serviceName);
	$output = $output['details'];
		if(!empty($output['name'])){

		$output = array("name"=>$output['name'],
						"address"=>$output['address'],
						"customernumber"=>"",
					    "account"=>$output['meterNumber']);
		$response['status'] = 200;
			$response['message'] = $output;
		}
		else{
			$response['status'] = 503;
			$response['message'] = "Verifying {$destination} not completed";
		}
		vend_response($response);
}

//Eko Postpaid
if($product_type == 14){
		
		require '/var/www/vhosts/api/vas/electricity/req_abuja_electric.php';
	$billerPublicId = "8E7485D9-1A67-4205-A49D-691E5B78C20D";
	
	$serviceName = "Post Paid";
	$output = verifyDetails($destination,$billerPublicId, $serviceName);
	$output = $output['details'];
		if(!empty($output['name'])){

		$output = array("name"=>$output['name'],
						"address"=>$output['address'],
						"customernumber"=>"",
					    "account"=>$output['meterNumber']);
		$response['status'] = 200;
			$response['message'] = $output;
		}
		else{
			$response['status'] = 503;
			$response['message'] = "Verifying {$destination} not completed";
		}
		vend_response($response);

}

//PHDC POSTPAID
if($product_type == 15){
		
		 $account = $destination;
		 require '/var/www/vhosts/api/vas/electricity/itex.php';
		$output = $output = api_call($requestPHverify, "http://197.253.19.75:8029/vas/phed/validation");
		$output = json_decode($output, true);
		

        if($output['status'] == 1 ){
             $output = array( 'name'=> $output['name'], 'address'=>$output['address'], 'meterNumber'=>$destination,'status' =>0, "customernumber"=>$output['productCode']);

			$response['status'] = 200;
			$response['message'] = $output;
		}
		else{
			$response['status'] = 503;
			$response['message'] = "Verifying {$destination} not completed";
		}
		vend_response($response);

}

if($product_type == 16){
		
		 $account = $destination;
		 require '/var/www/vhosts/api/vas/electricity/itex.php';
		$output = $output = api_call($requestPHverify, "http://197.253.19.75:8029/vas/phed/validation");
		$output = json_decode($output, true);

        if($output['status'] == 1 ){
             $output = array( 'name'=> $output['name'], 'address'=>$output['address'], 'meterNumber'=>$destination,'status' =>0, "customernumber"=>$output['productCode']);

			$response['status'] = 200;
			$response['message'] = $output;
		}
		else{
			$response['status'] = 503;
			$response['message'] = "Verifying {$destination} not completed";
		}
		vend_response($response);

}

//KEDCO Prepaid
if($product_type == "20"){
		
		 require '/var/www/vhosts/api/vas/electricity/fet.php';
                $fet = callkedco(0,$destination,0,1);

             if(is_array($fet) && $fet['responseCode'] == 0){
             	$custid = $fet['customerId'];
             	 $arr = explode("|",$custid);
                $output = array(
                  'address'=>$arr[1], 'name'=>$fet["message"],'customernumber'=>$custid,'account'=>$destination);

			$response['status'] = 200;
			$response['message'] = $output;
		}
		else{
			$response['status'] = 503;
			$response['message'] = "Verifying {$destination} not completed";
		}
		vend_response($response);
}




//EEDC Prepaid
if($product_type == 21){
		$presentProvider = getPresentProvider($type, $mysqli);
    $pProvider = $presentProvider[0]['provider_id'];
    if($pProvider == 4){
		 require '/var/www/vhosts/api/vas/electricity/fet.php';
                $mode='PRE';
                $fet = callenugudc(0,$destination,0, 1,0,$mode);
                //print_r($fet);
        if(!empty($fet['customerId'])){
                $custid = $fet['customerId'];
              
                $arr = explode("|",$custid);
                $output = array(
                  'address'=>$arr[4], 'name'=>$arr[3],'customernumber'=>$custid,'account'=>$destination);
			$response['status'] = 200;
			$response['message'] = $output;
		}
		else{
			$response['status'] = 503;
			$response['message'] = "Verifying {$destination} not completed";
		}
		vend_response($response);
    }
    if($pProvider == 5){
        include '/var/www/vhosts/api/vas/electricity/shagoapi.php';    
    if($type == 21){$meterType = "PREPAID";}
        if($type == 22){$meterType = "POSTPAID";}
        $content = array("serviceCode"=>"AOV","disco"=>"EEDC","meterNo"=>$account,"type"=>$meterType);
        $resp = shagoApi($content);
        $resp = json_decode($resp,true);
       
        if($resp['status'] == 200){
            $output = array(
		  'address'=>$resp['customerAddress'],'name'=>$resp['customerName'],'account'=>$resp['meterNo']);
            $response['status'] = 200;
			$response['message'] = $output;
        }
		else{
			$response['status'] = 503;
			$response['message'] = "Verifying {$destination} not completed";
		}
		vend_response($response);
        }
    }



//EEDC Postpaid
if($product_type == 22){
    $presentProvider = getPresentProvider($type, $mysqli);
    $pProvider = $presentProvider[0]['provider_id'];
    if($pProvider == 4){
		 require '/var/www/vhosts/api/vas/electricity/fet.php';

                $mode='POST';
                $fet = callenugudc(0,$destination,0, 1,0,$mode);

       if(!empty($fet['customerId'])){
                $custid = $fet['customerId'];
              
                $arr = explode("|",$custid);
                $output = array(
                  'address'=>$arr[4], 'name'=>$arr[3],'customernumber'=>$custid,'account'=>$destination);
			$response['status'] = 200;
			$response['message'] = $output;
		}
		else{
			$response['status'] = 503;
			$response['message'] = "Verifying {$destination} not completed";
		}
		vend_response($response);
    }
    if($pProvider == 4){
    include '/var/www/vhosts/api/vas/electricity/shagoapi.php';    
    if($type == 21){$meterType = "PREPAID";}
        if($type == 22){$meterType = "POSTPAID";}
        $content = array("serviceCode"=>"AOV","disco"=>"EEDC","meterNo"=>$account,"type"=>$meterType);
        $resp = shagoApi($content);
        $resp = json_decode($resp,true);
        if($resp['status'] == 200){
            $output = array(
		  'address'=>$resp['customerAddress'],'name'=>$resp['customerName'],'account'=>$resp['meterNo']);
            $response['status'] = 200;
			$response['message'] = $output;
        }
		else{
			$response['status'] = 503;
			$response['message'] = "Verifying {$destination} not completed";
		}
		vend_response($response);
        }

}

//AEDC Prepaid
if($product_type == 24){
			$account = $destination;
	$meterType = "0";
	$presentProvider = getPresentProvider($type, $mysqli);

		$pProvider = $presentProvider[0]['provider_id'];
	if($pProvider == 2){
		require '/var/www/vhosts/api/vas/electricity/itex.php';
		$output = api_call($requestABverify, "http://197.253.19.75:8029/vas/abuja/validation");
		$output = json_decode($output, true);
		if($output['status'] == 1){

		$output = array("name"=>$output['name'],
						"address"=>$output['address'],
						"customernumber"=>$output['productCode'],
					    "account"=>$destination);
		$response['status'] = 200;
			$response['message'] = $output;
		}else{
			$response['status'] = 503;
			$response['message'] = "Verifying {$destination} not completed";
		}
	}
	if($pProvider == 3){
			require '/var/www/vhosts/api/vas/electricity/req_abuja_electric.php';
		$serviceName = "Search by meter number (mr)";
		$billerPublicId = "13B5041B-7143-46B1-9A88-F355AD7EA1EC";
		$json_data = verifyDetails($destination,$billerPublicId, $serviceName);
	
		if(is_array($json_data["details"])){
			$data = $json_data["details"];
			$output = array(
							"name"=> $data["name"],
							"address"=>$data['address'],
							"customernumber"=>"",
							"account"=>$destination);
		$response["status"] = 200;
		$response["message"] = $output;
			}
			else{
			$response['status'] = 503;
			$response['message'] = "Verifying {$destination} not completed";
		}
	}
		
		vend_response($response);

}

if($product_type == 25){
			$account = $destination;
	$meterType = "2";
	
		require '/var/www/vhosts/api/vas/electricity/itex.php';
		$output = api_call($requestABverify, "http://197.253.19.75:8029/vas/abuja/validation");
		$output = json_decode($output, true);
		if($output['status'] == 1){

		$output = array("name"=>$output['name'],
						"address"=>$output['address'],
						"customernumber"=>$output['productCode'],
					    "account"=>$destination);
		$response['status'] = 200;
			$response['message'] = $output;
		}
		else{
			$response['status'] = 503;
			$response['message'] = "Verifying {$destination} not completed";
		}
		vend_response($response);

}

//DSTV
if($product_type == 30){
		$smartcard = $destination;
		require '/var/www/vhosts/api/vas/airVend/baxi/req_dstv_verify.php';
		$output = json_decode($output, true);
		$output = $output['details'];
		if($output['customerType'] != 'GOTVSUD' && !empty($output['customerType'])){
			$name= $output['firstName'].' '.$output['lastName'];
			$output = array("name"=>$name,
						"accountstatus"=>$output['accountStatus'],
						"customernumber"=>$output['customerNumber'],
					    "account"=>$destination);
		$response['status'] = 200;
			$response['message'] = $output;
		}
		else{
			$response['status'] = 503;
			$response['message'] = "Verifying {$destination} not completed";
		}
		vend_response($response);

}

//GOTV
if($product_type == 40){
		
		$smartcard = $destination;
		require '/var/www/vhosts/api/vas/airVend/baxi/req_gotv_verify.php';
		$output = json_decode($output, true);
		$output = $output['details'];
		
		if($output['customerType'] == 'GOTVSUD'){
			$name= $output['firstName'].' '.$output['lastName'];
			$output = array("name"=>$name,
						"accountstatus"=>$output['accountStatus'],
						"customernumber"=>$output['customerNumber'],
					    "account"=>$destination);
		$response['status'] = 200;
			$response['message'] = $output;
		}
		else{
			$response['status'] = 503;
			$response['message'] = "Verifying {$destination} not completed";
		}
		vend_response($response);
}

//SMILE
if($product_type == 50 || $product_type == 60){
		$customer = $destination;
		require'/var/www/vhosts/api/vas/airVend/baxi/req_smile_verify.php';
		$output = json_decode($output, true);
		$output = $output['details'];
		if(!empty($output['firstName'])){
			$name= $output['firstName'].' '.$output['lastName'];
			$output = array("name"=>$name,
						"accountstatus"=>"",
						"customernumber"=>"",
					    "account"=>$destination);

		$response['status'] = 200;
			$response['message'] = $output;
		}
		else{
			$response['status'] = 503;
			$response['message'] = "Verifying {$destination} not completed";
		}
		vend_response($response);

}

//STARTIMES
if($product_type == 70){
		$smartCardNumber = $destination;
		require'/var/www/vhosts/api/vas/airVend/baxi/req_startimes_verify.php';
		$output = json_decode($output, true);
		$output =$output['details'];
		if(!empty($output['customerName'])){
			$name= $output['customerName'];
			$output = array("name"=>$name,
						"address"=>"",
						"customernumber"=>"",
					    "account"=>$destination);

		$response['status'] = 200;
			$response['message'] = $output;
		}
		else{
			$response['status'] = 503;
			$response['message'] = "Verifying {$destination} not completed";
		}
		vend_response($response);
}

//BET9JA
if($product_type == "100"){
		
		 require '/var/www/vhosts/api/vas/electricity/fet.php';
                $fet = callbet9ja(0,$destination,0,1);
              
             if(is_array($fet) && $fet['responseCode'] == 0){
               $output = array( 'name'=> $fet['message'], 'account'=>$destination);

			$response['status'] = 200;
			$response['message'] = $output;
		}
		else{
			$response['status'] = 503;
			$response['message'] = "Verifying {$destination} not completed";
		}
		vend_response($response);
}
// TODO - Postpaid: calculate balance due for account
