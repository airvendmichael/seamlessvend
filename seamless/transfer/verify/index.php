<?php

//echo 'This service is SUSPENDED';
//exit();

error_reporting(~E_ALL);
ini_set('display_errors', 0); 

include '../../function/db.inc.php';
include '../../function/func.inc.php';
include '../../function/json_func.php';
include '../function.php';
include '../providus.php';
include '../provider.php';


//The Server IP Address
$request_ip         = print_r($_SERVER['REMOTE_ADDR'],1);

//Treating the headers
$query_st = getallheaders();

$user = $query_st['username'];
$pass = $query_st['password'];
$hash = $query_st['hash'];

$date = date("Y-m-d");
$log_string = "\n***********************************\n\n" . $date .'REQUEST: ' . $request_ip .
                                "\n" . 'user: ' . $user ."\n".$pass.
                                "\n\n";

error_log($log_string,3,'vtu2_request.log');





//Getting the content
$input= file_get_contents('php://input');

//{"details":{ "settlement_bank": "058029", "account_number": "0123456789" }}

$data = json_decode($input, TRUE);
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
if($test != $hash){
$response['status'] = 403;
	$response['message'] = "Failed Third Level Verification";
	vend_response($response);
}

//Call Banks
$bank = $data['details']['settlementBank'];
$account = $data['details']['account'];
if($provider == 1){
//Payant
	$payload = array("settlement_bank"=>$bank, "account_number"=>$account);
	$payload = json_encode($payload);
	$output = verifyAcc($payload);
	$output = json_decode($output, TRUE);
	if($output['status'] == "success"){
		$status = TRUE;
		$data = $output['data'];
	}
}
else{
	//Providus
	$mx = new ProvidusTransfer;
	$data =  ["accountNumber"=>$account, "bankCode"=>$bank];
	$output = $mx->verifyAccount($data);
	$output = json_decode($output, TRUE);
	if($output["responseCode"]=="00"){
		$status = TRUE;
		$data = array('settlement_bank'=>$bank, 'account_number'=>$account, "account_name"=>$output['accountName']);
	}
}


	


if($status == TRUE){
				$resd['status'] = 200;
				$resd['message'] = $data;
				vend_response($resd);
}
else{
	$resd['status'] = 417;
	$resd['message'] = $output;
	vend_response($resd);
}