v<?php

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

//Money Transfer Type
$type = 1000;


//Getting the content
$input= file_get_contents('php://input');

//{"details":{ "settlementBank": "058029", "account": "0123456789" }}

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

$bank = $data['details']['settlementBank'];
$account = $data['details']['account'];
$destination = $account;
$amount = $data['details']['amount'];
$amount = number_format((float)$amount, 2, '.', '');
$remark = $data['details']['remark'];
$ref = $data['details']['ref'];

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

if($amount<=0){

						$response['status'] = 402;
						$response['message'] = "Invalid amount Selected";
						$response['TransactionID'] = $transaction_id;
						$response['networkid'] = $network_id;
						$response['account'] = $account;
						$response['Balance'] = $vendor_credit;
						$response['referenceID'] = $ref;
						$response['amount'] = $amount;
			
						vend_response($response);
}

if($vendor['user_type'] == 1) {
	
	//echo 'CREDIT CHECK';
		
			// PRE PAID
	
			// Check Available CREDIT
			// Check for auto top up <- check distributor account
			$vendor_credit = getVendorBalance($vendor['vendor_id'],$mysqli);
		
			$vendor_comm   = getVendorElectricityComm($vendor['vendor_id'],$type,$mysqli);
		
			$cost = $amount + $vendor_comm['commission'];
			
		
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



//Call Banks

$payload = array("settlement_bank"=>$bank, "account_number"=>$account, "amount"=>$amount, "remark"=>$remark);
$payload = json_encode($payload);



$time_start = microtime(true);


$date = date('Y-m-d H:i:s');
$log_string = "\n\n" . $date . ' |Start Processor include: '.json_encode($payload). "\n";
error_log($log_string,3,'vtu2_request.log');

//***************************************************************************
if($provider ==1){
	// Payant
	$output = transferMoney($payload);
	$output = json_decode($output, TRUE);
	if($output['status'] == "success"){
		$status = TRUE;
		$res= $output['data'];
		$settlement_bank=$res["settlement_bank"];
		$account_name=$res["account_name"];
		$account_number=$res["account_number"];
		$updated_at=$res["updated_at"];
		$created_at=$res["created_at"];
		$gateway_response=$res["gateway_response"];
		$disburse_status=$res["disburse_status"];
		$disburse_ref=$res["disburse_ref"];

		$response = array("settlement_bank"=>$settlement_bank,
			"transaction_id"=>$transaction_id,
			"account_name"=>$account_name,
			"account_number"=>$account_number,
			"gateway_response"=>$gateway_response,
			"disburse_status"=>$disburse_status,
			"disburse_ref"=>$disburse_ref);
	}

}
else{
	//Providus

	$mx = new ProvidusTransfer;
	$data =  ["name"=>"Callphone LTD","amount"=>$amount, "narration"=>$remark, "accountNumber"=>$account, "bankCode"=>$bank, "ref"=>"CPL-".$transaction_id];
	$output = $mx->transferFund($data);
	$output = json_decode($output, TRUE);
	if($output["responseCode"] === "00"){
		$status = TRUE;

		$response = array("settlement_bank"=>$bank,
			"transaction_id"=>$transaction_id,
			"account_name"=>$data["name"],
			"account_number"=>$account,
			);
	}

}



//**************************************************************************

$response = $output;
 
//print_R($response);
//exit();
$date = date('Y-m-d H:i:s');
$log_string = "\n\n" . $date . ' |End Processor include: '.$net['airtime_processor']. "\n";
error_log($log_string,3,'vtu2_request.log');
$time_end  = microtime(true);
$et        = $time_end - $time_start;

if($status == TRUE){
	$result = 0;
}
else{
	$result = "999999";
}


$log_sql = "UPDATE transaction_log set 							
				vendor_id					= ".$vendor['vendor_id'].",
				distributor_id				= ".$vendor['distributor_id'].",				
				transaction_type			= 20,
				destination					= '".$destination."',
				value						= $amount,
				transaction_date			= NOW(),
				elapsed_time				= ".round($et,2).",
				transaction_data_request	= '".print_r($input,1)."', 
				transaction_data_result		= '".print_r($response, 1)."', 
				transaction_error			= '$error',
				status_code					= '$result',
				product_type				= $type
			WHERE transaction_id            = $transaction_id";				
				
$log_res        = mysqli_query($mysqli,$log_sql);


$res = $output['data'];



if($result == 0){

	$wallet_pre = getVendorBalance($vendor['vendor_id'],$mysqli);
	
		// Vendor Comm
		$distributor_comm = getDistributorElectricityComm($vendor['distributor_id'],$type,$mysqli);
		
		
		if($vendor['user_type'] == 1) {
			// Pre-paid - update wallet balance
			//echo "DEBUG: DEDUCTING CREDIT: ".$vendor['vendor_id']." : ".$amt;
			$cost = $amount + $distributor_comm['commission'];
				deductVendorCredit($vendor['vendor_id'],$cost,$mysqli);
		}	
		
		logComm($vendor['vendor_id'],
						1,
						0,
						$transaction_id,
						-$distributor_comm['commission'],
						-$distributor_comm['commission'],
						0,
						0,
						$mysqli);


		/*		
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

				*/
		
		$balance = getVendorBalance($vendor['vendor_id'],$mysqli);
                
                $log_sql = "UPDATE transaction_log set
                                wallet_pre                  = '$wallet_pre',
                                wallet_post                 = '$balance'
                        WHERE transaction_id = $transaction_id";

                $log_res        = mysqli_query($mysqli,$log_sql);


				$resd['status'] = 200;
				$resd['tranasactionID'] = $transaction_id;
				$resd['message'] = $response;
				vend_response($resd);
}
else{
	$resd['status'] = 417;
	$resd['message'] = $output;
	vend_response($resd);
}
