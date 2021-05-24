<?php

function getPresentProvider($type, $mysqli){

$sql = "SELECT  * FROM service_available_provider WHERE type = $type ";    

  $res = mysqli_query($mysqli,$sql);
  
  
  while($t = mysqli_fetch_assoc($res)) {
    
      $trans[] = $t;  
  }
  return $trans;

}

function ipaddress($linked_id, $ip_address, $mysqli){
    $sql = "SELECT * FROM iptable WHERE vendorid = '$linked_id' AND ip_address = '$ip_address'";
    $query = mysqli_query($mysqli, $sql);
    $num = mysqli_num_rows($query);
    if($num >= 1){
        return TRUE;
    }
    else{
        return FALSE;
    }
}


function get_Password($pass){
        $prefix = "aRY&xUxNY#RJ";
        $suffix = "NJSLSIiJixs%@Na";

        $salt = $prefix.$pass.$suffix;
        $algo = 'SHA256';
        $count = 10;

        for($i=1; $i<=$count; $i++){
                $salt = hash('SHA256', $salt);
        }
        return $salt;

}

function logRequest($msisdn,$sessionid,$ussd,$mysqli) {
	
	$sql = "INSERT INTO ussd_request(msisdn,sessionid,ussd_data,ts) VALUES('$msisdn','$sessionid','$ussd',NOW())";
	$res = mysqli_query($mysqli,$sql);	
	
}

function getVendorAuth($user,$pass,$mysqli) {
	
	//$pass = mysqli_real_escape_string($mysqli,$pass);
	//$user = mysqli_real_escape_string($mysqli,$user);
	
	
	$sql = "SELECT * FROM userauth
			JOIN vendors ON linked_id = vendor_id
			WHERE userauth.user_type = '1' 
			AND userauth.email = '".$user."' AND userauth.pass = '".$pass."'";
	$res = mysqli_query($mysqli,$sql);
//echo $sql;	
	$row_cnt = mysqli_num_rows($res);
	
	if($row_cnt == 0) {
		return FALSE;
	} else {
		$vendor = mysqli_fetch_assoc($res);	
		return $vendor;
	};
}


function getVendor($msisdn,$mysqli) {
	
	$sql = "SELECT * FROM userauth
			JOIN vendors ON linked_id = vendor_id
			WHERE userauth.msisdn = '$msisdn' AND userauth.user_type = 1";
	$res = mysqli_query($mysqli,$sql);
	
	$row_cnt = mysqli_num_rows($res);
//echo $sql;	
	if($row_cnt == 0) {
		return FALSE;
	} else {
		$vendor = mysqli_fetch_assoc($res);	
		return $vendor;
	};
}

function getNetwork($networkid,$mysqli) {
	
	$sql = "SELECT * FROM networkid_map where network_id = '$networkid'";
	$res = mysqli_query($mysqli,$sql);
	
	$net = mysqli_fetch_assoc($res);	

	return $net;
	
}

function getNetworkMenu($vendor_id,$sessionid,$mysqli) {
	
	// Build a Network Menu for USSD based on the enabled networks and the Vendors Allow Networks
	
	$enabled_sql = "SELECT * FROM networkid_map where enabled = 1 
					AND network_id IN(SELECT network_id from vendor_networks where vendor_id = $vendor_id)
					ORDER BY network_name ASC";
	$res_enabled = mysqli_query($mysqli,$enabled_sql);

	$i=0;	
	while ($n = mysqli_fetch_assoc($res_enabled)) {
		
			$i++;
			$net[$i] = array( 'network_name' => $n['network_name'], 'network_id' => $n['network_id']);
			
			// TODO. whati f more than 8 items? requires a "MORE" on meue
	}
	
	//print_r($net);
	// Serialize and store to DB for refernece on answer
	$serialize_menu = serialize($net);
	$serialize_menu = mysqli_escape_string($mysqli,$serialize_menu);
	$sql_store = "UPDATE ussd_usersession SET carrier_menu = '$serialize_menu' WHERE sessionid = '$sessionid'";
	$res_store = mysqli_query($mysqli,$sql_store);
	
	//print $sql_store;
	//print mysqli_error($mysqli);
	
	
	return $net;
	
}

function fetchSerializedCarrierMenu($sessionid,$mysqli) {
	
	$sql = "SELECT carrier_menu FROM ussd_usersession where sessionid = '$sessionid'";
	$res = mysqli_query($mysqli,$sql);
	
	$data = mysqli_fetch_assoc($res);
	
	$menu = unserialize($data['carrier_menu']);
	
	return $menu;
		
}

function fetchSerializedAmountMenu($sessionid,$mysqli) {
	
	$sql = "SELECT amount_menu FROM ussd_usersession where sessionid = '$sessionid'";
	$res = mysqli_query($mysqli,$sql);
	
	$data = mysqli_fetch_assoc($res);
	
	$menu = unserialize($data['amount_menu']);
	
	return $menu;
		
}


function getAmount($valueid,$mysqli) {
	
	if($valueid > 9) {
		// Direct value supplied
		$net['value'] = $valueid;
	};
	
	$sql = "SELECT * FROM ussd_valuemap where valueid = '$valueid'";
	$res = mysqli_query($mysqli,$sql);
	
	$net = mysqli_fetch_assoc($res);	

	return $net;	
	
}

function getAmountsMenu($type,$sessionid,$mysqli) {
	
	$sql = "SELECT value from ussd_valuemap where type = '$type'";
	$res = mysqli_query($mysqli,$sql);
	
	$values = mysqli_fetch_all($res,MYSQL_ASSOC);
	
	$i=0;	
	foreach($values as $v) {
			$i++;
			$value[$i] = $v;
	}
	
	// Serialize and store to DB for refernece on answer
	$serialize_menu = serialize($value);
	$serialize_menu = mysqli_escape_string($mysqli,$serialize_menu);
	$sql_store = "UPDATE ussd_usersession SET amount_menu = '$serialize_menu' WHERE sessionid = '$sessionid'";
	$res_store = mysqli_query($mysqli,$sql_store);
	
	return $value;
	

};

function getVendorElectricityComm($vendor_id,$product_type,$mysqli) {
	
	$sql = "SELECT commission from vendor_vas_commission 
			WHERE vendor_id = $vendor_id
			AND product_type  = $product_type";
	$res = mysqli_query($mysqli,$sql);

	
	$comm = mysqli_fetch_assoc($res);

	return $comm;
	
}

function getDistributorElectricityComm($distributor_id,$product_type,$mysqli) {
	
	$sql = "SELECT commission from distributor_vas_commission 
			WHERE distributor_id = $distributor_id
			AND product_type  = $product_type";
	$res = mysqli_query($mysqli,$sql);
// echo $sql;
// print mysqli_error($mysqli);	
	$comm = mysqli_fetch_assoc($res);
	
	return $comm;
	
}



function getProviderComm($type,$mysqli) {
	
	// GET Network Commission base rate
	$sql_nc = "SELECT base_rate from product_type where product_type_id = " . $type;
	$res_nc = mysqli_query($mysqli,$sql_nc);	
	$nc     = mysqli_fetch_assoc($res_nc);
	
	return $nc;


}


function getVendorBalance($vendor_id,$mysqli) {
	
	$sql = "SELECT balance from vendor_wallet where vendor_id = $vendor_id	";
	$res = mysqli_query($mysqli,$sql);

	if(mysqli_num_rows($res) == 0) {
		$balance = '0';
	} else {
		$b       = mysqli_fetch_assoc($res);
		$balance = $b['balance'];
	}
	
	return $balance;
};

function deductVendorCredit($vendor_id,$amt=0,$mysqli) {
	
	$sql = "UPDATE vendor_wallet set balance = balance - $amt where vendor_id = $vendor_id";
	//echo $sql;
	//echo mysqli_error($mysqli);
	
	$res = mysqli_query($mysqli,$sql);
	
}

function deductCarrierCredit($vendor_id,$amt=0,$mysqli) {
	
	$sql = "UPDATE carrier_credit set balance = balance - $amt where vendor_id = $vendor_id";
	//echo $sql;
	
	$res = mysqli_query($mysqli,$sql);
	
}

function getSummaryReport($vendor_id,$period,$mysqli) {
	
	// Determine period SQL
	if($period == 'day') {
		$period_sql = "AND date_format(transaction_date,'%Y-%m-%d') = '".date('Y-m-d')."'";
	}
	
	if($period == 'yesterday') {
		$period_sql = "AND date_format(transaction_date,'%Y-%m-%d') = DATE_SUB(NOW(), INTERVAL 1 DAY)";
	}
	
	if($period == 'month') {
		$period_sql = "AND MONTH(transaction_date) = " . date('m');
	}
	
	if($period == 'lastmonth') {
		$lastmonth = $date('m') -1;
		
		$period_sql = "AND MONTH(transaction_date) = " . date('m')-1;
	}
	
	if($period == 'week') {
		$ddate = date('Y-m-d');
		$date = new DateTime($ddate);
		$week = $date->format("W");
		$period_sql = "AND YEARWEEK(transaction_date) = ". $week;
	}
	
	if($period == 'lastweek') {
		$ddate = date('Y-m-d');
		$date = new DateTime($ddate);
		$week = $date->format("W");
		$week = $week - 1;
		$period_sql = "AND YEARWEEK(transaction_date) = ". $week-1;
	}
	
	// Count transactions
	$trans_cnt_sql = "SELECT transaction_id,value FROM transaction_log WHERE					
					 vendor_id = $vendor_id
					 AND status_code = 0
					 $period_sql";
	//print $trans_cnt_sql;
	$res           = mysqli_query($mysqli,$trans_cnt_sql);
	$cnt           = mysqli_num_rows($res);
	
	$total_sales = 0;
	$comm_earned = 0;
	while($t = mysqli_fetch_assoc($res)) {
		
		$total_sales = $total_sales + $t['value'];
		// print_r($t);
		
		$trans_comm =getTransactionComm($t['transaction_id'],$vendor_id,1,$mysqli);
		$comm_value = $t['value'] * ($trans_comm/100); 		
		$comm_earned = $comm_earned + $comm_value;
		
		// echo "total sales: $total_sales, trans_comm: $trans_comm, comm_value: $comm_value, comm earned: $comm_earned";
		
		
	}
	
	$stats = array("count" => $cnt, "gross" => $total_sales, "commission" => $comm_earned);
	return $stats;
	 
};

function getTransactionComm($transaction_id,$linked_id,$user_type,$mysqli) {
	
	$sql = "SELECT commission from transaction_comm where transaction_id = $transaction_id
	       AND linked_id = $linked_id
		   AND user_type  = $user_type";
		   
		  
    $res = mysqli_query($mysqli,$sql);
	
	if($res === FALSE) {
		$comm['commission'] = 0;
	} else {
		$comm = mysqli_fetch_assoc($res);
	}
	
	return $comm['commission'];
}

function getVendorComm($vendor_id,$network_id,$mysqli) {
	
	$sql = "SELECT network_id,commission from vendor_vtu_commission 
			WHERE vendor_id = $vendor_id
			AND network_id  = $network_id";
	$res = mysqli_query($mysqli,$sql);

	
	$comm = mysqli_fetch_assoc($res);

	return $comm;
	
}

function getDistributorComm($distributor_id,$network_id,$mysqli) {
	
	$sql = "SELECT network_id,commission from distributor_vtu_commission 
			WHERE distributor_id = $distributor_id
			AND network_id  = $network_id";
	$res = mysqli_query($mysqli,$sql);
	
	$comm = mysqli_fetch_assoc($res);
	
	return $comm;
	
	
	
}

function getDistributorCommChain($distributor_id,$network_id,$mysqli) {

	// get this distibtuor comm.	
	$sql = "SELECT commission from distributor_vtu_commission 
			WHERE distributor_id = $distributor_id
			AND network_id  = $network_id";
	$res = mysqli_query($sql);
	
	// put in array
	//if parent is not 1, get parent distributor
	
	$comm_arr = mysqli_fetch_assoc($res);
	
	
	return $comm;	
}

function saleCreditDistributor($transaction_id,$vendor_id,$distributor_id,$vendor_comm,$dist_comm,$sale_amount,$mysqli) {

	// Vendor Comm Value
	$v_comm = $sale_amount * ($vendor_comm/100);
	
	// Dist Comm Value
	$d_comm = $sale_amount * ($dist_comm/100);
	
	//  Difference
	$comm_diff = $d_comm - $v_comm;
	
	// Log Sale Commission
	$sql = "INSERT INTO distributor_sale_credit(transaction_id,vendor_id,distributor_id,sale_amount,vendor_comm,dist_comm,ts)
			VALUES($transaction_id,$vendor_id,$distributor_id,$sale_amount,$v_comm,$d_comm,NOW())";
	$res = mysqli_query($mysqli,$sql);

//echo '<br /><br />' . $sql;
	
	// Update Wallet
	$note = 'Sale Commission';
	addDistributorFunds($distributor_id,$comm_diff,"$note",$mysqli);

	
}

function getCarrierCom($carrier_id,$mysqli) {
	
	// GET Network Commission base rate
	$sql_nc = "SELECT base_rate from networkid_map where network_id = " . $carrier_id;
	$res_nc = mysqli_query($mysqli,$sql_nc);	
	$nc     = mysqli_fetch_assoc($res_nc);
	
	return $nc;


}

function logComm($linked_id='',$user_type='',$commission='',$transaction_id='',$dist_comm='',$dist_comm_value='',$carrier_comm='',$carrier_comm_value='',$mysqli) {

	
	if(empty($commission)) { $commission=0; };
	
	if(empty($transaction_id)) { $transaction_id=0; };
	
	if(empty($dist_comm)) { $dist_comm=0; };
	
	if(empty($dist_comm_value)) { $dist_comm_value=0; };
	
	if(empty($carrier_comm)) { $carrier_comm=0; };
	
	if(empty($carrier_comm_value)) { $carrier_comm_value=0; };

	
		
	//	echo $sql;
	
	// Calculate the commission
		// Get sale value
		$trans_sql = "SELECT * FROM transaction_log WHERE transaction_id = $transaction_id";
		$trans_res = mysqli_query($mysqli,$trans_sql);
		
		$trans = mysqli_fetch_assoc($trans_res);
		
		// Calc transaction comm value
	   $comm_value = $trans['value'] * ($commission/100); 
	   
	  // Write to log
	   $sql = "INSERT INTO transaction_comm(linked_id,user_type,commission,transaction_id,comm_value,dist_comm,dist_comm_value,carrier_comm,carrier_comm_value,ts)
				VALUES($linked_id,$user_type,$commission,$transaction_id,$comm_value,$dist_comm,$dist_comm_value,$carrier_comm,$carrier_comm_value,NOW())";
		$res = mysqli_query($mysqli,$sql);
	   
	 //  echo $sql;
	 //  echo mysqli_error($mysqli);	
		
		
}

function getLastTransaction($vendor_id,$mysqli) {
	
	$sql = "SELECT * from transaction_log WHERE vendor_id = '$vendor_id' 
			order by transaction_date DESC LIMIT 1";
	$res = mysqli_query($mysqli,$sql);

	$t   = mysqli_fetch_assoc($res);
	
	return $t;
	
}

function autoTopupVendor($vendorid,$mysqli) {

	// is vendor enabled for autotop up?
	
	// IF DISTRIBUTOR PREPAID:
		// Determine Distributor Wallet Balance- 
	
		// Check if enought in Wallet for Top up action
		
		// if Tru - TOP UP
		
		// Else -> send notification
		
	
	
}

function addDistributorFunds($distributor_id,$amount,$note='',$mysqli) {
	
	$sql = "INSERT INTO distributor_wallet(distributor_id,balance)
			VALUES($distributor_id,balance+$amount)
			ON DUPLICATE KEY UPDATE balance = balance+$amount";
	$res = mysqli_query($mysqli,$sql);

//echo '<br /><br />' . $sql;

	
	//Record transaction
	/*
	$sql = "INSERT INTO distributor_credit_record(distributor_id,note,credit,ts)
			VALUES($distributor_id,'$note',$amount,NOW())";			
	$res = mysqli_query($mysqli,$sql);
	*/

//echo '<br /><br />' . $sql;

		
}



function getMNOAcc($distributor_id,$network_id,$mysqli) {
	
	
	
	$sql = "SELECT * from mno_account WHERE distributor_id = $distributor_id 
	        AND network_id = $network_id";
	$res = mysqli_query($mysqli,$sql);
	
	//echo $sql;
	
		
	if(mysqli_num_rows($res) == 0 ) {
		$sql_nomno = "SELECT * from mno_account WHERE distributor_id = 0 
	        AND network_id = $network_id";		
	    $res_nomno = mysqli_query($mysqli,$sql_nomno);			
		$data = mysqli_fetch_assoc($res_nomno);	
		
		//echo 'MNO DATA: ' . print_r($data);	
		return $data;
					
	} else {
		$data = mysqli_fetch_assoc($res);		
		return $data;
	}
}











	
	

	
	
	
	
	
