<?php
// Get the phone number from the POST data
$phone_number = $_POST['phone_number'];
//MPESA AUTHORIZATION
$consumerKey = "naP4p43KMfj2u1JcMuWIao5Jo6rlC0ak"; //Fill with your app Consumer Key
$consumerSecret = "TOwCRj7Hw0kWxxVy"; //Fill with your app Consumer Secret
//ACCESS TOKEN URL
$access_token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
$headers = ['Content-Type:application/json; charset=utf8'];
$curl = curl_init($access_token_url);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_HEADER, FALSE);
curl_setopt($curl, CURLOPT_USERPWD, $consumerKey . ':' . $consumerSecret);
$result = curl_exec($curl);
$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
$result = json_decode($result);
$access_token = $result->access_token;
curl_close($curl);


//STKPUSH
date_default_timezone_set('Africa/Nairobi');
$processrequestUrl = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
$callbackurl = 'https://1c95-105-161-14-223.ngrok-free.app/MPEsa-Daraja-Api/callback.php';
$passkey = "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919";
$BusinessShortCode = '174379';
$Timestamp = date('YmdHis');
// ENCRIPT  DATA TO GET PASSWORD
$Password = base64_encode($BusinessShortCode . $passkey . $Timestamp);
$phone = $phone_number;//phone number to receive the stk push
$money = '1000';
$PartyA = $phone;
$PartyB = '254708374149';
$AccountReference = 'REALMARK JOBS';
$TransactionDesc = 'stkpush test';
$Amount = $money;
$stkpushheader = ['Content-Type:application/json', 'Authorization:Bearer ' . $access_token];
//INITIATE CURL
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $processrequestUrl);
curl_setopt($curl, CURLOPT_HTTPHEADER, $stkpushheader); //setting custom header
$curl_post_data = array(
  //Fill in the request parameters with valid values
  'BusinessShortCode' => $BusinessShortCode,
  'Password' => $Password,
  'Timestamp' => $Timestamp,
  'TransactionType' => 'CustomerPayBillOnline',
  'Amount' => $Amount,
  'PartyA' => $PartyA,
  'PartyB' => $BusinessShortCode,
  'PhoneNumber' => $PartyA,
  'CallBackURL' => $callbackurl,
  'AccountReference' => $AccountReference,
  'TransactionDesc' => $TransactionDesc
);

$data_string = json_encode($curl_post_data);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
$curl_response = curl_exec($curl);
//ECHO  RESPONSE
$data = json_decode($curl_response);
$CheckoutRequestID = $data->CheckoutRequestID;
$ResponseCode = $data->ResponseCode;
if ($ResponseCode == "0") {
    echo "Initiating MPESA payment for phone number: $phone_number";;
}
else {
    echo "Please check your Number and Try Again";
}
$transaction_successful = true;

if ($transaction_successful) {
    // If the transaction is successful, store the form data in the database
    storeFormDataInDatabase($_POST);
    echo "Transaction successful. Form data stored in the database.";
} else {
    // If the transaction fails, prompt the user to retry
    echo "Transaction failed. Please retry the payment.";
}

function storeFormDataInDatabase($formData) {
    // TODO: Implement the logic to store form data in the database
    // For now, let's just print the data
    print_r($formData);
}
?>