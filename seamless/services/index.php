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
$data= file_get_contents('php://input');



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
$ipaddress = ipaddress($vendor['linked_id'],$request_ip,$mysqli);
if($ipaddress == FALSE){
	$response['status'] = 403;
	$response['message'] = "Failed Second Level Verification";
	vend_response($response);
}

//Test Hashing with hashing Key
$test = $data.$vendor['hash_key'];
$test = hash('sha512', $test);
if($test != $hash){
$response['status'] = 401;
	$response['message'] = "Failed Third Level Verification";
	vend_response($response);
}

function services($mysqli){
$query = "SELECT service_name, type_id, network_id, product, verify FROM service";
$result = mysqli_query($mysqli, $query);
 while($res = mysqli_fetch_assoc($result)){
 	$array[]= array("Service" => $res['service_name'],
 					"Type" => $res['type_id'],
 					"NetworkID" => $res['network_id'],
 					"Product" => $res['product'],
					"verify" => $res['verify']);
 
 }
 return $array;
}

if($vendor['user_type'] == 1) { 
		// PRE PAID

		// Check Available CREDIT
		// Check for auto top up <- check distributor account
		$response['status'] = 200;
		$response['message'] = services($mysqli);
		
		vend_response($response);

} 

// TODO - Postpaid: calculate balance due for account
