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
$query_st = getallheaders();


$date = date("Y-m-d");
$log_string = "\n***********************************\n\n" . $date .'REQUEST: ' . $request_ip .
                                "\n" . 'user: ' . $user ."\n".$pass.
                                "\n\n";

error_log($log_string,3,'vtu2_request.log');

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
$test = hash('sha512', $test);
if($test != $hash){
$response['status'] = 401;
	$response['message'] = "Failed Third Level Verification";
	vend_response($response);
}

function transaction($transactionid, $mysqli){
$query = "SELECT status_code, transaction_id, client_reference, destination, transaction_date, value, product_type FROM transaction_log WHERE transaction_id = $transactionid";
$result = mysqli_query($mysqli, $query);
 while($res = mysqli_fetch_assoc($result)){
 	$array[]= array("status" => $res['status_code'],
 					"destination" => $res['destination'],
 					"date" => $res['transaction_date'],
 					"amount" => $res['value'],
 					"type" => $res['product_type'],
 					"ref"=> $res['client_reference'],
 					"trans" => $res['transaction_id']);

 
 }
 return $array;
}

function referenceTransaction($tran, $mysqli){

$query = "SELECT status_code, transaction_id, client_reference, destination, transaction_date, value, product_type FROM transaction_log WHERE client_reference = '".$tran."'";
$result = mysqli_query($mysqli, $query);

 while($res = mysqli_fetch_assoc($result)){
 	$array[]= array("status" => $res['status_code'],
 					"destination" => $res['destination'],
 					"date" => $res['transaction_date'],
 					"amount" => $res['value'],
 					"type" => $res['product_type'],
 					"ref"=> $res['client_reference'],
 					"trans" => $res['transaction_id']);

 
 }

 return $array;
}


//IF DATA BUNDLE
$transactionid = $data['details']['transactionid'];
$ref = $data['details']['ref'];
if(empty($ref)){
$trans = transaction($transactionid, $mysqli);
}
else{
$trans = referenceTransaction($ref, $mysqli);
}

if($trans['0']['status'] === '0'){
//
			$response['status'] = 200;
		$response['message'] = "Successful Transaction ".$trans['0']['date'];
		$response['amount'] = $trans['0']['amount'];
		$response['type'] = $trans['0']['type'];
		$response['account'] = $trans['0']['destination'];
		$response['TransactionID'] = $trans['0']['trans'];
		$response['referenceID'] = $trans['0']['ref'];
}

elseif($trans['0']['status'] > "0"){
		$response['status'] = 501;
		$response['message'] = "Failed Transaction ".$trans['0']['date'];
		$response['amount'] = $trans['0']['amount'];
		$response['type'] = $trans['0']['type'];
		$response['account'] = $trans['0']['destination'];
		$response['TransactionID'] = $trans['0']['trans'];
		$response['referenceID'] = $trans['0']['ref'];
}

else{
			$response['status'] = 404;
			$response['message'] = "Transaction not Found on Server";
		}

vend_response($response);

// TODO - Postpaid: calculate balance due for account
