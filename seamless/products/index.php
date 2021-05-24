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
$ipaddress = ipaddress($vendor['linked_id'],$request_ip,$mysqli);
if($ipaddress == FALSE){
	$response['status'] = 403;
	$response['message'] = "Failed Second Level Verification";
	vend_response($response);
}

//Test Hashing with hashing Key
$test = $input.$vendor['hash_key'];
$hash = strtolower($hash);
$test = hash('sha512', $test);
$test = strtolower($test);
if($test != $hash){
$response['status'] = 401;
        $response['message'] = "Failed Third Level Verification";
        vend_response($response);
}


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
$network_id = $data['details']['networkid'];
$destination = $data['details']['account'];
$productcode = $data['details']['code'];

$type = $product_type;


	if($product_type == 2){
		if(empty($network_id)){
			$response['status'] = 411;
			$response['message'] = "Network ID is missing";

			vend_response($response);
		}
		
		$response['status'] = 200;
		$response['message'] = dataplan($network_id, $mysqli);
		
		vend_response($response);

}
//DSTV

if($product_type == 30){
	
	require'/var/www/vhosts/api/vas/airVend/baxi/req_dstv_product.php';
	$output = json_decode($output, true);

if(!empty($output['details']['items'])){

	$inside = $output["details"]["items"];


    $i = sizeof($inside);

    for($a =0; $a<=$i-1; $a++ ){

    $price = $inside[$a]['availablePricingOptions'][0]['price'];

    $code = $inside[$a]['code'];

    $name = $inside[$a]['name'];
    $nName = $inside[$a]['name'];

    $description = $inside[$a]['description'];

     $al_array = array("descrition" => $description,
     					"Amount" => $price,
     					"code" => $code,
                             "name" => $name
                            );
    $final_array[] =$al_array;

    //Add Ons
    $productcode = $code;
	require '/var/www/vhosts/api/vas/airVend/baxi/req_dstv_addon_product.php';
	$arr =json_decode($output, true);

  	if(!empty($in = $arr['details']['items'])){

		$in = $arr["details"]["items"];


    		$j = sizeof($in);
                for($b =0; $b<=$j-1; $b++ ){                                                                                                                                                                                                             
    		$price = $in[$b]['availablePricingOptions'][0]['price'];

    		$code = $in[$b]['code'];

    		$name = $in[$b]['name'];

    		$description = $in[$b]['description'];
     		$al_array = array("descrition" => $description,
                            "Amount" => $price,
                            "code" => $code,
                             "name" => $nName." ".$name
                         );
	    	$final_array[] =$al_array;
		}
	}

    }
    
	$response['status'] = 200;
		$response['message'] = $final_array;
}
else{
			$response['status'] = 503;
			$response['message'] = "Kindly try again Later";
		}

vend_response($response);
}

//GOTV
if($product_type == 40){

	require'/var/www/vhosts/api/vas/airVend/baxi/req_gotv_product.php';
	$output = json_decode($output, true);

if(!empty($output['details']['items'])){

	$inside = $output["details"]["items"];


    $i = sizeof($inside);

    for($a =0; $a<=$i-1; $a++ ){

    $price = $inside[$a]['availablePricingOptions'][0]['price'];

    $code = $inside[$a]['code'];

    $name = $inside[$a]['name'];

    $description = $inside[$a]['description'];

     $al_array = array("descrition" => $description,
     					"Amount" => $price,
     					"code" => $code,
                             "name" => $name
                            );
    $final_array[] =$al_array;

    }
    
	$response['status'] = 200;
		$response['message'] = $final_array;
}
else{
			$response['status'] = 503;
			$response['message'] = "Kindly try again Later";
		}

vend_response($response);

}

//SMILE BUNDLE

if($product_type == 60){
		$customer = $destination;
		require'/var/www/vhosts/api/vas/airVend/baxi/req_smile_product.php';
		$output = json_decode($output, true);	
		$output = $output['details']['bundles'];
		if(!empty($output)){
	$i = sizeof($output);

    for($a =0; $a<=$i-1; $a++ ){

    	$validity ="";
    	$description = $output[$a]['description'];
    	$code = $output[$a]['typeCode'];
    	$amount = $output[$a]['amount'];
 
    	$array[] = array("descrition" => $description,
     					"Amount" => $amount,
     					"code" => $code,
                             "Validity" => $validity
    							);
		}
			$response['status'] = 200;
		$response['message'] = $array;
}
else{
			$response['status'] = 503;
			$response['message'] = "Kindly try again Later";
		}

vend_response($response);


}
//WAEC
if($product_type == 80){
		require'/var/www/vhosts/api/vas/airVend/baxi/req_waec_product.php';
		$output = json_decode($output, true);	
		$output = $output['details'];
		if(!empty($output)){
		$output = array("amount" => $output['pinValues'][0]['amount'],
						"count" => $output['pinValues'][0]['count']);


			$response['status'] = 200;
		$response['message'] = $output;
}
else{
			$response['status'] = 503;
			$response['message'] = "Kindly try again Later";
		}
	vend_response($response);	
}

if($product_type == 81){
	require"/var/www/vhosts/api/vas/electricity/req_abuja_electric.php";
	$output = jambProducts();

	$output = json_decode($output, true);
	$output = $output['billerServices'];
	foreach($output as $k){
	$array[] = array("name"=>$k['serviceName'],
					"amount"=>$k['servicePrice'],
					"code"=>$k['serviceName']);
	}

	$response['status'] = 200;
	$response['message'] = $array;

	vend_response($response);

}
//SPECTRANET
if($product_type == 90){
	require'/var/www/vhosts/api/vas/airVend/baxi/req_spectranet.php';
	$output = Spectranet_Pins();
	$output = json_decode($output, true);
	$output = $output['details']['pinValues'];

	

	if(!empty($output)){
	$i = sizeof($output);

    for($a =0; $a<=$i-1; $a++ ){

    	$validity ="";
    	
    	$code = $output[$a]['count'];
    	$amount = $output[$a]['amount'];
 
    	$array[] = array("descrition" => "PIN Purchase of {$amount}",
     					"Amount" => $amount,
     					"count" => $code,
                             "Validity" => $validity
    							);
		}
			$response['status'] = 200;
		$response['message'] = $array;
}
else{
			$response['status'] = 503;
			$response['message'] = "Kindly try again Later";
		}

vend_response($response);
}

// TODO - Postpaid: calculate balance due for account
