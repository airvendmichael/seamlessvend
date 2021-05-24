
<?php

function payant($url, $payload){
        $url = "https://api.payant.ng/wallets/nMmheQqaY9";
        $pass = "824d527ff93eefdc6d5abb6f3973dfe6725eaa15a9045d420f11e301";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json","Authorization: Bearer {$pass}"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

      return $response;
}

function bankCall(){
        $url = "https://api.payant.ng/banks";
        $pass = "824d527ff93eefdc6d5abb6f3973dfe6725eaa15a9045d420f11e301";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json","Authorization: Bearer {$pass}"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

      return $response;
}

function verifyAcc($payload){
        $url = "https://api.payant.ng/resolve-account";
        $pass = "824d527ff93eefdc6d5abb6f3973dfe6725eaa15a9045d420f11e301";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json","Authorization: Bearer {$pass}"));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

      return $response;
}

function transferMoney($payload){
        $url = "https://api.payant.ng/wallets/withdraw/nMmheQqaY9";
        $pass = "824d527ff93eefdc6d5abb6f3973dfe6725eaa15a9045d420f11e301";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json","Authorization: Bearer {$pass}"));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

      return $response;
}