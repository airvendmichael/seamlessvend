<?php
//echo 'This service is SUSPENDED';
//exit();

error_reporting(~E_ALL);
ini_set('display_errors', 0); 

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

$date = date("Y-m-d");
$log_string = "\n***********************************\n\n" . $date .'REQUEST: ' . $request_ip .
                                "\n" . 'user: ' . $user ."\n".$pass.
                                "\n\n";

error_log($log_string,3,'vtu2_request.log');





//Getting the content
$input= file_get_contents('php://input');

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


//Verify Type to direct Response

/*
	MNO Transactions
*/
$product_type = $data['details']['type'];

$type = $product_type;

if(!empty($type)){
 require "input.php";
}
else{
	$response['status'] = 405;
	$response['message'] = "Selected Type is Not Allowed";
	vend_response($response);
}

//print_r($_REQUEST);
$net    = getNetwork($network_id,$mysqli);

// LOG REQUEST
$date           = date('Y m d H:i:s');
$server         = print_r($_SERVER,1);
$request        = print_r($_REQUEST,1);
$vendor_log     = print_r($vendor,1);

$log_string = "\n***********************************\n\n" . $date .'REQUEST: ' . $input . 
				"\n" . 'SERVER: ' . $server .
				"\n" . 'VENDOR:  ' . $vendor_log .
				"\n\n";
				
error_log($log_string,3,'vtu2_request.log');



if(!empty($ref)) {
	
	$sql_chk        = "SELECT * FROM transaction_log WHERE client_reference ='$ref' AND vendor_id = ".$vendor['vendor_id'];
	
	
	
	$res_chk		= mysqli_query($mysqli,$sql_chk);
	$chk_cnt		= mysqli_num_rows($res_chk);
	
	//echo $sql_chk;
	//echo mysqli_error($mysqli);
	
	if($chk_cnt >= 1) {
	
		$response['status'] = 409;
		$response['message'] = "Transaction Already Exist";
		vend_response($response);
		
	}
}

$date = date('Y-m-d H:i:s');

$log_string = "\n\n" . $date . ' |TYPE: REFERENCE TIME | TransactionID: '.$transaction_id. "\n";

error_log($log_string,3,'vtu2_request.log');


$log_sql 		= "INSERT INTO transaction_log(client_reference,vendor_id,transaction_date) 
					VALUES('$ref',".$vendor['vendor_id'].",NOW())";
$log_res        = mysqli_query($mysqli,$log_sql);
$transaction_id = mysqli_insert_id($mysqli);

$date = date('Y-m-d H:i:s');
//$log_string = "\n" . $log_sql .' | ' . mysqli_error($mysqli);
$log_string = "\n\n" . $date . ' |TYPE: TRANSACTION LOG | TransactionID: '.$transaction_id. "\n";

error_log($log_string,3,'vtu2_request.log');

/******* 3rd party mno accounts API LOGIC****
* Check is default or custom MNO account
* if default  TEMP: set vendor ['user_type'] to 0 (skips billing balance check as is API)

*/


// Below is simple work around and temporary until business case adjusts.
// set the user type to 0 to skip billing. billing is per transactio
/*
echo '<pre>MA: ';
print_r($ma);
echo '</pre>';
*/

//GET MNO DETAILS FROM DB
if($type <= 4 || $type == 27){

$ma = getMNOAcc($vendor['distributor_id'],$network_id,$mysqli);

if($ma['default_account'] == '0') {
	$vendor['user_type'] = 0;
	//echo 'DEBUG: set vendor type 0';
}
}

if($vendor['user_type'] == 1) {
	
	//echo 'CREDIT CHECK';
		
			// PRE PAID
	
			// Check Available CREDIT
			// Check for auto top up <- check distributor account
			$vendor_credit = getVendorBalance($vendor['vendor_id'],$mysqli);
		
			$vendor_comm   = getVendorComm($vendor['vendor_id'],$net['network_id'],$mysqli);
		
			$cost = $amount - ($amount * ($vendor_comm['commission']/100));
			$max_amount = 5000;  //MNO TRANSACTION LIMIT
	//echo 'DEBUG: CHECK CREDIT <br />';
		
			if($cost > $vendor_credit){
				
								
						error_log($log_string,3,'vtu2_request.log');

						$response['status'] = 402;
						$response['message'] = "Stock Value Too Low to perform transaction";
						$response['TransactionID'] = $transaction_id;
						$response['networkid'] = $network_id;
						$response['account'] = $account;
						$response['Balance'] = $vendor_credit;
						$response['referenceID'] = $ref;
						$response['amount'] = $amount;
			
						vend_response($response);

			
		}
}



// Sufficient credit			
// Start ET Timer
$time_start = microtime(true);


$date = date('Y-m-d H:i:s');
$log_string = "\n\n" . $date . ' |Start Processor include: '.$net['airtime_processor']. "\n";
error_log($log_string,3,'vtu2_request.log');



// ************************** INCLUDE PROCESSOR ***************************
//require "call.php";
// ************************************************************************


$date = date('Y-m-d H:i:s');
$log_string = "\n\n" . $date . ' |End Processor include: '.$net['airtime_processor']. "\n";
error_log($log_string,3,'vtu2_request.log');

/*
print_r(
	$error.' '.$transaction_id.' '.$destination.' '.$customername.' '.	$amount.' '.$ref .' '.$type .' '.$contacttype.' '.$customernumber.' '.$customeraddress.' '.$customerphone .' '.$customeremail);
exit();
*/
$time_end  = microtime(true);
$et        = $time_end - $time_start;


$date = date('Y-m-d H:i:s');
$log_string  = "\n\n" . $date . ' |REQUEST: '.$request . "\n";
$log_string .= "\n\n" . $date . ' |RESPONSE: '.$response . "\n";
$log_string .= "\n\n" . $date . ' |RESULT: '.$result . "\n";
$log_string .= "\n\n" . $date . ' |RESULT_TXT: '.$result_txt . "\n";
error_log($log_string,3,'vtu2_request.log');
	
				


if(strlen($result) == 0) {
	$result = 99999999;
}

// Catch unset product type (airtime/Data)



$request  = mysqli_real_escape_string($mysqli,$request);
$error    = mysqli_real_escape_string($mysqli,$error);

$product_type  = mysqli_real_escape_string($mysqli,$product_type);

if($type <= 4 || $type == 27){
$log_sql = "UPDATE transaction_log set 							
				vendor_id					= ".$vendor['vendor_id'].",
				distributor_id				= ".$vendor['distributor_id'].",
				network_id					= $network_id,
				transaction_type			= 10,
				destination					= '$account',
				value						= $amount,
				transaction_date			= NOW(),
				elapsed_time				= ".round($et,2).",
				transaction_data_request	= '$input', 
				transaction_data_result		= '$response',
				transaction_error			= '$error',
				status_code					= '$result',
				product_type				= $product_type,
				mno_account                 = ".$ma['acc_id']."
				WHERE transaction_id 		= $transaction_id";	


				}			

				if($type > 9 && $type != 27){
					
$log_sql = "UPDATE transaction_log set 							
				vendor_id					= ".$vendor['vendor_id'].",
				distributor_id				= ".$vendor['distributor_id'].",
				transaction_type			= 10,
				destination					= '$account',
				value						= $amount,
				transaction_date			= NOW(),
				elapsed_time				= ".round($et,2).",
				transaction_data_request	= '".print_r($input,1)."', 
				transaction_data_result		= '".print_r($response,1)."',
				transaction_error			= '$error',
				status_code					= '$result',
				product_type				= $product_type
				WHERE transaction_id 		= $transaction_id";	

				}		
				
$log_res        = mysqli_query($mysqli, $log_sql);
//$transaction_id = mysqli_insert_id($mysqli); //Set earlier

//echo $log_sql;
//print mysqli_error($mysqli);



// Log Commissions
// Get commission for vendor

if($result == '0') {
	
		// ** MNO ACC: if account is default (0) run billing updates
	if($type <=4 || $type == 27){
		if($ma['default_account'] == 1) {
				if($network_id != 6) {
						// Vendor Comm
						$wallet_pre = getVendorBalance($vendor['vendor_id'],$mysqli);
						$vendor_comm       = getVendorComm($vendor['vendor_id'],$network_id,$mysqli);
						
						if($vendor['user_type'] == 1) {
							// Pre-paid - update wallet balance
							//echo "DEBUG: DEDUCTING CREDIT: ".$vendor['vendor_id']." : ".$amt;
							$cost = $amount - ($amount * ($vendor_comm['commission']/100));	
							deductVendorCredit($vendor['vendor_id'],$cost,$mysqli);
						}					
						
						// Get commission for Distributor
						$distributor_comm = getDistributorComm($vendor['distributor_id'],$network_id,$mysqli);
						$nc               = getCarrierCom($network_id,$mysqli);
											
						$dist_comm_value    = $amount * ($distributor_comm['commission']/100);
						$carrier_comm_value = $amount * ($nc['base_rate']/100);
						
						logComm($vendor['vendor_id'],
								1,
								$vendor_comm['commission'],
								$transaction_id,
								$distributor_comm['commission'],
								$dist_comm_value,
								$nc['base_rate'],
								$carrier_comm_value,
								$mysqli);
								
						saleCreditDistributor($transaction_id,$vendor['vendor_id'],$vendor['distributor_id'],$vendor_comm['commission'],$distributor_comm['commission'],$amount,$mysqli);
				} else {
					
					$date = date('Y-m-d H:i:s');
					$log_string  = "\n\n" . $date . ' |DUMMY TEST TrasnactionID: '.$transaction_id . "\n";
					
					error_log($log_string,3,'vtu2_request.log');	
				}
					
					$balance = getVendorBalance($vendor['vendor_id'],$mysqli);
		}
		/*$output = "SUCCESS. Balance: $balance\n";
		$output .= "Ref: $transaction_id\n ";
		$output.= 'Network: ' . $net['network_name'] . "\n";
		$output.= 'Amount: N' . $amount . "\n";
		$output.= 'To: ' . $s['destination'] . "\n";
		*/
	$log_sql = "UPDATE transaction_log set	
				wallet_pre                  = '$wallet_pre',
                                wallet_post                 = '$balance'
                        WHERE transaction_id = $transaction_id";

   $log_res        = mysqli_query($mysqli,$log_sql);
		}

		if($type > 9 && $type != 27){
			                $wallet_pre = getVendorBalance($vendor['vendor_id'],$mysqli);
	
		// Vendor Comm
		$vendor_comm       = getVendorElectricityComm($vendor['vendor_id'],$type,$mysqli);
		
		if($vendor['user_type'] == 1) {
			// Pre-paid - update wallet balance
			//echo "DEBUG: DEDUCTING CREDIT: ".$vendor['vendor_id']." : ".$amt;
			$cost = $amount - ($amount * ($vendor_comm['commission']/100));	
				deductVendorCredit($vendor['vendor_id'],$cost,$mysqli);
		}	
		
				
		// Get commission for Distributor
		$distributor_comm = getDistributorElectricityComm($vendor['distributor_id'],$type,$mysqli);
		$nc               = getProviderComm($type,$mysqli);

		$dist_comm_value    = $amount * ($distributor_comm['commission']/100);
		$carrier_comm_value = $amount * ($nc['base_rate']/100);
		
					logComm($vendor['vendor_id'],
						1,
						$vendor_comm['commission'],
						$transaction_id,
						$distributor_comm['commission'],
						$dist_comm_value,
						$nc['base_rate'],
						$carrier_comm_value,
						$mysqli);
						
				saleCreditDistributor($transaction_id,$vendor['vendor_id'],$vendor['distributor_id'],$vendor_comm['commission'],$distributor_comm['commission'],$amount,$mysqli);		
		
		$balance = getVendorBalance($vendor['vendor_id'],$mysqli);
                
                $log_sql = "UPDATE transaction_log set
                                wallet_pre                  = '$wallet_pre',
                                wallet_post                 = '$balance'
                        WHERE transaction_id = $transaction_id";

                $log_res        = mysqli_query($mysqli,$log_sql);
		}
	
		// Parse EPIN
require "response.php";
} 
else {

                        $resd['status'] = 417;
						$resd['message'] = $result.' '.$error." FAILED | SERVICE_UNAVAILABLE";
						$resd['TransactionID'] = $transaction_id;
						$resd['networkid'] = $net['network_id'];
						$resd['account'] = $destination;
						$resd['Balance'] = $vendor_credit;
						$resd['referenceID'] = $ref;
						$resd['amount'] = $amount;
						$resd['type'] = $product_type;
		
						vend_response($resd);
		
}

