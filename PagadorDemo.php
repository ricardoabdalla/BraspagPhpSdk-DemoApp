<?php

require ("vendor/autoload.php");

use BraspagSdk\Common\Environment;
use BraspagSdk\Contracts\Pagador\CreditCardData;
use BraspagSdk\Contracts\Pagador\CustomerData;
use BraspagSdk\Contracts\Pagador\MerchantCredentials;
use BraspagSdk\Contracts\Pagador\PaymentDataRequest;
use BraspagSdk\Contracts\Pagador\SaleRequest;
use BraspagSdk\Pagador\PagadorClient;
use BraspagSdk\Pagador\PagadorClientOptions;

$credentials = new MerchantCredentials("33B6AC07-C48D-4F13-A5B9-D3516A378A0C", "d6Rb3OParKvLfzNrURzwcT0f1lzNazS1o19yP6Y8");
$options = new PagadorClientOptions($credentials, Environment::SANDBOX);

$request = new SaleRequest();

$request->MerchantOrderId = uniqid();

$request->Customer = new CustomerData();
$request->Customer->Name = "Bjorn Ironside";
$request->Customer->Identity = "762.502.520-96";
$request->Customer->IdentityType = "CPF";
$request->Customer->Email = "bjorn.ironside@vikings.com.br";

$request->Payment = new PaymentDataRequest();
$request->Payment->Provider = "Simulado";
$request->Payment->Type = "CreditCard";
$request->Payment->Currency = "BRL";
$request->Payment->Country = "BRA";
$request->Payment->Amount = 1000;
$request->Payment->Installments = 1;
$request->Payment->SoftDescriptor = "Braspag SDK";
$request->Payment->Capture = false;
$request->Payment->Authenticate = false;
$request->Payment->Recurrent = false;
$request->Payment->Credentials = null;
$request->Payment->Assignor = null;
$request->Payment->DebitCard = null;
$request->Payment->FraudAnalysis = null;
$request->Payment->ExternalAuthentication = null;
$request->Payment->Wallet = null;
$request->Payment->RecurrentPayment = null;
$request->Payment->ExternalAuthentication = null;
$request->Payment->ReturnUrl = null;

$request->Payment->CreditCard = new CreditCardData();
$request->Payment->CreditCard->CardNumber = "4485623136297301";
$request->Payment->CreditCard->Holder = "BJORN IRONSIDE";
$request->Payment->CreditCard->ExpirationDate = "12/2025";
$request->Payment->CreditCard->SecurityCode = "123";
$request->Payment->CreditCard->Brand = "visa";

$client = new PagadorClient($options);
$response = $client->createSale($request);

var_dump($response);