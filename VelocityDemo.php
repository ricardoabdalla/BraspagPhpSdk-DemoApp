<?php

use BraspagSdk\BraspagAuth\BraspagAuthClient;
use BraspagSdk\Common\ClientOptions;
use BraspagSdk\Common\Environment;
use BraspagSdk\Common\OAuthGrantType;
use BraspagSdk\Contracts\BraspagAuth\AccessTokenRequest;
use BraspagSdk\Contracts\Pagador\CustomerData;
use BraspagSdk\Contracts\Velocity\AddressData;
use BraspagSdk\Contracts\Velocity\AnalysisRequest;
use BraspagSdk\Contracts\Velocity\CardData;
use BraspagSdk\Contracts\Velocity\MerchantCredentials;
use BraspagSdk\Contracts\Velocity\PhoneData;
use BraspagSdk\Contracts\Velocity\TransactionData;
use BraspagSdk\Velocity\VelocityClient;
use BraspagSdk\Velocity\VelocityClientOptions;

require ("vendor/autoload.php");



class VelocityDemo
{
    public function run()
    {
        echo "VELOCITY\n";
        echo "=====================================\n";

        /* Criação do Token de Acesso OAUTH via Braspag Auth */
        $authOptions = new ClientOptions();
        $authOptions->Environment = Environment::SANDBOX;
        $braspagAuthClient = new BraspagAuthClient($authOptions);

        $authRequest = new AccessTokenRequest();
        $authRequest->ClientId = "5d85902e-592a-44a9-80bb-bdda74d51bce";
        $authRequest->ClientSecret = "mddRzd6FqXujNLygC/KxOfhOiVhlUr2kjKPsOoYHwhQ=";
        $authRequest->GrantType = OAuthGrantType::ClientCredentials;
        $authRequest->Scope = "VelocityApp";

        $authResponse = $braspagAuthClient->createAccessToken($authRequest);

        /* Criação do Cliente Velocity */
        $credentials = new MerchantCredentials("94E5EA52-79B0-7DBA-1867-BE7B081EDD97", $authResponse->Token);
        $velocityOptions = new VelocityClientOptions($credentials);
        $velocityClient = new VelocityClient($velocityOptions);

        /* Analisando uma transação com Velocity */
        $analysisResponse = $this->PerformAnalysis($velocityClient);

        echo "Transaction analyzed\n";
        echo "Score: " . $analysisResponse->AnalysisResult->Score .  "\n";
        echo "Status: " . $analysisResponse->AnalysisResult->Status . "\n";
        echo "Accept By WhiteList: " . $analysisResponse->AnalysisResult->AcceptByWhiteList . "\n";
        echo "Reject By BlackList: " . $analysisResponse->AnalysisResult->RejectByBlackList . "\n";
        echo "\n";
    }

    private function PerformAnalysis(VelocityClient $velocityClient)
    {
        $request = new AnalysisRequest();

        $request->Transaction = new TransactionData();
        $request->Transaction->OrderId = uniqid();
        $request->Transaction->Date = date("");
        $request->Transaction->Amount = 1000;

        $request->Card = new CardData();
        $request->Card->Holder = "BJORN IRONSIDE";
        $request->Card->Brand = "Visa";
        $request->Card->Number = "1000100010001000";
        $request->Card->Expiration = "10/2025";

        $request->Customer = new CustomerData();
        $request->Customer->Name = "Bjorn Ironside";
        $request->Customer->Identity = "76250252096";
        $request->Customer->IpAddress = "127.0.0.1";
        $request->Customer->Email = "bjorn.ironside@vikings.com.br";
        $request->Customer->Birthdate = "1982-06-30";
        $request->Customer->Phones = array();

        $phone = new PhoneData();
        $phone->Type = "Cellphone";
        $phone->Number = "999999999";
        $phone->DDI = "55";
        $phone->DDD = "11";
        array_push($request->Customer->Phones, $phone);

        $request->Customer->Billing = new AddressData();
        $request->Customer->Billing->Street = "Alameda Xingu";
        $request->Customer->Billing->Number = "512";
        $request->Customer->Billing->Neighborhood = "Alphaville";
        $request->Customer->Billing->City = "Barueri";
        $request->Customer->Billing->State = "SP";
        $request->Customer->Billing->Country = "BR";
        $request->Customer->Billing->ZipCode = "06455-030";

        return $velocityClient->performAnalysis($request);
    }
}

$demo = new VelocityDemo();
$demo->run();