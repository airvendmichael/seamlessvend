<?php

class ProvidusTransfer{

    var $base_url = "http://192.168.156.39:8888/postingrest/";
    var $username = "callphone";
    var $password = "5@!!P90NE_P50()_p@y";

    private function api_call($data, $endpoint){
        $payload = json_encode($data);
        print_r($payload);
        print_r( $this->base_url.$endpoint);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->base_url.$endpoint);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        if(!empty($data)){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function verifyAccount($data){
        $endpoint = "GetNIPAccount";
        $payload = ["accountNumber"=>$data["accountNumber"], 
                    "beneficiaryBank"=>$data["bankCode"], 
                    "userName"=>$this->username,
                    "password"=>$this->password];
        return $this->api_call($payload, $endpoint);
    }

    public function getBankList(){
        $endpoint = "GetNIPBanks";
        return $this->api_call("", $endpoint);
    }

    public function transferFund($data){
        $endpoint = "NIPFundTransfer";
        $payload = ["beneficiaryAccountNumber"=>$data['accountNumber'], 
                    "beneficiaryBank"=>$data['bankCode'], 
                    "beneficiaryAccountName"=>$data["name"],
                    "transactionAmount"=>$data["amount"],
                    "narration"=>$data["narration"],
                    "sourceAccountName"=>"Callphone Limited",
                    "transactionReference"=>$data["ref"],
                    "currencyCode"=>"NGN",
                    "userName"=>$this->username,
                    "password"=>$this->password];
        return $this->api_call($payload, $endpoint);
    }

    public function accountBalance($data){
        
    }

    public function confirmTransaction($data){
        $endpoint = "GetProvidusTransactionStatus";
        $payload = ["transactionReference"=>$data["reference"], 
                    "userName"=>$this->username,
                    "password"=>$this->password];
        return $this->api_call($payload, $endpoint);

    }

}