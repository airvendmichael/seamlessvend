<?php

if($type <= 4 || $type == 27){
	if($type == 3) {

				if($network_id == 3){
			$s = explode(":", $ep['senum']);
			$p = explode(":", $ep['scnum']);
			$e = explode(":", $ep['expires']);
			$ep['scnum'] = $p[1];
			$ep['senum'] = $s[1];
			$ep['expires'] = $e[1];
		}

		$array = array('creditToken' => $ep['scnum'],
							'serialNumber' => $ep['senum'],
								'Expires' => $ep['expires'],
								'value' => $amount);
				} 	

						$resd['creditToken'] = $array;
						$resd['status'] = 200;
						$resd['message'] = $result_txt." ".$error;
						$resd['TransactionID'] = $transaction_id;
						$resd['networkid'] = $network_id;
						$resd['account'] = $dest_msisdn;
						$resd['Balance'] = $balance;
						$resd['referenceID'] = $ref;
						$resd['amount'] = $amount;
						$resd['type'] = $type;
						
						vend_response($resd);
		
		


}

//Ikeja Prepaid
if($type==10) {
	

						$resd['status'] = 200;
						$resd['message'] = " SUCCESS ".$vendData['details']['errorCode'];
						$resd['TransactionID'] = $transaction_id;
						$resd['account'] = $account;
						$resd['Balance'] = $balance;
						$resd['referenceID'] = $transref;
						$resd['amount'] = $amount;
						$resd['type'] = $type;
						
						vend_response($resd);
	
}
	
	//Ikeja Prepaid
if($type==11) {		

					$array = array('creditToken' => chunk_split($token, 4, ' '),
							'serialNumber' => "",
								'Expires' => "",
								'value' => $unit );
					$resd['creditToken'] = $array;
	

						$resd['status'] = 200;
						$resd['message'] = " SUCCESS ".$vendData['details']['errorCode'];
						$resd['TransactionID'] = $transaction_id;
						$resd['account'] = $account;
						$resd['Balance'] = $balance;
						$resd['referenceID'] = $transref;
						$resd['amount'] = $amount;
						$resd['type'] = $type;
						
						vend_response($resd);
}

//Ibadan Pretpaid
if($type==12){
	 			$array = array('creditToken' => $token,
							'serialNumber' => $vendData['transactionId'],
								'Expires' => "",
								'value' => $unit );

       
        				$resd['creditToken'] = $array;
					 //Vend Tarrif

						$resd['status'] = 200;
						$resd['message'] = " SUCCESS ".$vendData['details']['errorCode'];
						$resd['TransactionID'] = $transaction_id;
						$resd['account'] = $destination;
						$resd['Balance'] = $balance;
						$resd['referenceID'] = $ref;
						$resd['amount'] = $amount;
						$resd['type'] = $type;
						
						vend_response($resd);
}

if($type==23) {
	

						$resd['status'] = 200;
						$resd['message'] = " SUCCESS ";
						$resd['TransactionID'] = $transaction_id;
						$resd['account'] = $account;
						$resd['Balance'] = $balance;
						$resd['referenceID'] = $transref;
						$resd['amount'] = $amount;
						$resd['type'] = $type;
						
						vend_response($resd);
	
}


if($type==13) {
	
//	echo '<pre>';this is Eko prepaid
//	print_r($vendData);
//	echo '</pre>'

					$array = array('creditToken' => chunk_split($output['confirmationCode'],4, ' '),
							'serialNumber' => $output['transactionId'],
								'Expires' => "",
								'value' => $unit );
					$resd['creditToken'] = $array;


						$resd['status'] = 200;
						$resd['message'] = " SUCCESS ".$output['details']['errorCode'];
						$resd['TransactionID'] = $transaction_id;
						$resd['account'] = $destination;
						$resd['Balance'] = $balance;
						$resd['referenceID'] = $ref;
						$resd['amount'] = $amount;
						$resd['type'] = $type;
						
						vend_response($resd);
	
} 
if ($type == 14) {
	//eko postpaid

  

						$resd['status'] = 200;
						$resd['message'] = " SUCCESS ".$output['details']['errorCode'];
						$resd['TransactionID'] = $transaction_id;
						$resd['account'] = $destination;
						$resd['Balance'] = $balance;
						$resd['referenceID'] = $ref;
						$resd['amount'] = $amount;
						$resd['type'] = $type;
						
						vend_response($resd);
    }


//PHED POSTPAID
if($type==15) {
	

						$resd['status'] = 200;
						$resd['message'] = " SUCCESS ";
						$resd['TransactionID'] = $transaction_id;
						$resd['account'] = $account;
						$resd['Balance'] = $balance;
						$resd['referenceID'] = $transref;
						$resd['amount'] = $amount;
						$resd['type'] = $type;
						
						vend_response($resd);
	
}
	
	//PHEDC Prepaid
if($type==16) {		

					$array = array('creditToken' => chunk_split($token, 4, ' '),
							'serialNumber' => "",
								'Expires' => "",
								'value' => $unit );
					$resd['creditToken'] = $array;
	

						$resd['status'] = 200;
						$resd['message'] = " SUCCESS ";
						$resd['TransactionID'] = $transaction_id;
						$resd['account'] = $account;
						$resd['Balance'] = $balance;
						$resd['referenceID'] = $transref;
						$resd['amount'] = $amount;
						$resd['type'] = $type;
						
						vend_response($resd);
}


//KEDCO Prepaid

if($type==20) {
	
//	echo '<pre>';
//	print_r($vendData); 
//	echo '</pre>';


					$array = array('creditToken' => chunk_split($token, 4, ' '),
							'serialNumber' => $vendData['details']['tokenAmount'],
								'Expires' => "",
								'value' => "" );

					   	$resd['creditToken'] = $array;
						$resd['status'] = 200;
						$resd['message'] = " SUCCESS ".$vendData['details']['errorCode'];
						$resd['TransactionID'] = $transaction_id;
						$resd['account'] = $destination;
						$resd['Balance'] = $balance;
						$resd['referenceID'] = $ref;
						$resd['amount'] = $amount;
						$resd['type'] = $type;
						
						vend_response($resd);
	
} 

//Enugu Prepaid
	
if($type==21) {
	
//	echo '<pre>';
//	print_r($vendData); 
//	echo '</pre>';


					$array = array('creditToken' => chunk_split($token, 4, ' '),
							'serialNumber' => $vendData['details']['tokenAmount'],
								'Expires' => "",
								'value' => "" );

					   	$resd['creditToken'] = $array;
						$resd['status'] = 200;
						$resd['message'] = " SUCCESS ".$vendData['details']['errorCode'];
						$resd['TransactionID'] = $transaction_id;
						$resd['account'] = $destination;
						$resd['Balance'] = $balance;
						$resd['referenceID'] = $ref;
						$resd['amount'] = $amount;
						$resd['type'] = $type;
						
						vend_response($resd);
	
} 

//EEDC Post paid
if($type==22) {
	
//	echo '<pre>';
//enugu; 
//	echo '</pre>';
	$array = array('creditToken' => chunk_split($token, 4, ' '),
							'serialNumber' => $vendData['transactionId'],
								'Expires' => "",
								'value' => "" );


                     $resd['creditToken'] = $array;
	
						$resd['status'] = 200;
						$resd['message'] = " SUCCESS ".$vendData['details']['errorCode'];
						$resd['TransactionID'] = $transaction_id;
						$resd['account'] = $destination;
						$resd['Balance'] = $balance;
						$resd['referenceID'] = $ref;
						$resd['amount'] = $amount;
						$resd['type'] = $type;
						
						vend_response($resd);
}


//Abuja Prepaid
if($type == 24){


	
					$array = array('creditToken' => chunk_split($token,4, ' '),
							'serialNumber' => $transref,
								'Expires' => "",
								'value' => $unit );


                     $resd['creditToken'] = $array;

						$resd['status'] = 200;
						$resd['message'] = " SUCCESS ".$vendData['details']['errorCode'];
						$resd['TransactionID'] = $transaction_id;
						$resd['account'] = $destination;
						$resd['Balance'] = $balance;
						$resd['referenceID'] = $ref;
						$resd['amount'] = $amount;
						$resd['type'] = $type;
						
						vend_response($resd);
	}


//DSTV

	if($type == 30){

						$resd['status'] = 200;
						$resd['message'] = $vendData['details']['auditReferenceNumber']." SUCCESS ".$vendData['details']['errorCode'];
						$resd['TransactionID'] = $transaction_id;
						$resd['account'] = $destination;
						$resd['Balance'] = $balance;
						$resd['referenceID'] = $ref;
						$resd['amount'] = $amount;
						$resd['type'] = $type;
						
						vend_response($resd);

}

//GOTV

if($type == 40){
	$resd['status'] = 200;
						$resd['message'] = $vendData['details']['auditReferenceNumber']." SUCCESS ".$vendData['details']['errorCode'];
						$resd['TransactionID'] = $transaction_id;
						$resd['account'] = $destination;
						$resd['Balance'] = $balance;
						$resd['referenceID'] = $ref;
						$resd['amount'] = $amount;
						$resd['type'] = $type;
						
						vend_response($resd);

		}
		
//STARTIMES
if($type == 70){
						$resd['status'] = 200;
						$resd['message'] = " SUCCESS ".$vendData['details']['errorCode'];
						$resd['TransactionID'] = $transaction_id;
						$resd['account'] = $destination;
						$resd['Balance'] = $balance;
						$resd['referenceID'] = $ref;
						$resd['amount'] = $amount;
						$resd['type'] = $type;
						
						vend_response($resd);
}

//SPECTRANET
if($type == 90){
	$json_data = json_decode($output, true);
	foreach ($json_data['details']['pins'] as $ep) {

                         $array[] = array('creditToken' => $ep['pin'],
							'serialNumber' => $ep['serialNumber'],
								'Expires' => $ep['expiresOn'],
								'value' => $ep['value'] );

                     }

                     $resd['creditToken'] = $array;
                     $resd['status'] = 200;
						$resd['message'] = " SUCCESS ".$vendData['details']['errorCode'];
						$resd['TransactionID'] = $transaction_id;
						$resd['account'] = $destination;
						$resd['Balance'] = $balance;
						$resd['referenceID'] = $ref;
						$resd['amount'] = $amount;
						$resd['type'] = $type;

						vend_response($resd);
				
                }          

//WAEC
if($type == 80){
$pin_xml = '';
	foreach($json_data['details']['pins'] as $ep) {
	
		  $array[] = array('creditToken' => $ep['pin'],
							'serialNumber' => $ep['serialNumber'],
								'Expires' => $ep['expiresOn'],
								'value' => $ep['value'] );

                     }

                     $resd['creditToken'] = $array;
                     $resd['status'] = 200;
						$resd['message'] = " SUCCESS ".$json_data['details']['errorCode'];
						$resd['TransactionID'] = $transaction_id;
						$resd['account'] = $destination;
						$resd['Balance'] = $balance;
						$resd['referenceID'] = $ref;
						$resd['amount'] = $amount;
						$resd['type'] = $type;
				vend_response($resd);
}

//JAMB
if($type == 81){
$pin_xml = '';
	
	
		  $array = array('creditToken' => $j['confirmationCode'],
							'serialNumber' => "",
								);

                     

                     $resd['creditToken'] = $array;
                     $resd['status'] = 200;
						$resd['message'] = " SUCCESS ";
						$resd['TransactionID'] = $transaction_id;
						$resd['account'] = $destination;
						$resd['Balance'] = $balance;
						$resd['referenceID'] = $ref;
						$resd['amount'] = $amount;
						$resd['type'] = $type;
				vend_response($resd);
}

//SMILE RECHARGE
if($type == 50){

						$resd['status'] = 200;
						$resd['message'] = " SUCCESS ".$vendData['details']['errorCode'];
						$resd['TransactionID'] = $transaction_id;
						$resd['account'] = $destination;
						$resd['Balance'] = $balance;
						$resd['referenceID'] = $ref;
						$resd['amount'] = $amount;
						$resd['type'] = $type;
						
						vend_response($resd);
}

//SMILE BUNDLE
if($type == 60){
						$resd['status'] = 200;
						$resd['message'] = " SUCCESS ".$vendData['details']['errorCode'];
						$resd['TransactionID'] = $transaction_id;
						$resd['account'] = $destination;
						$resd['Balance'] = $balance;
						$resd['referenceID'] = $ref;
						$resd['amount'] = $amount;
						$resd['type'] = $type;
						
						vend_response($resd);
}

//BET9JA
if($type == 100){
						$resd['status'] = 200;
						$resd['message'] = " SUCCESS ".$vendData['details']['errorCode'];
						$resd['TransactionID'] = $transaction_id;
						$resd['account'] = $destination;
						$resd['Balance'] = $balance;
						$resd['referenceID'] = $ref;
						$resd['amount'] = $amount;
						$resd['type'] = $type;
						
						vend_response($resd);
}
