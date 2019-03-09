<?php

require ("../vendor/autoload.php");

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

/* Exemplo de código para analisar uma transação com Velocity Check */
/* Para maiores informações, consulte a documentação do produto em https://braspag.github.io/manual/velocity */
class VelocityDemo
{
    public function run()
    {
        echo nl2br("VELOCITY\n");
        echo nl2br("=====================================\n");

        /* Criação do Token de Acesso OAUTH via Braspag Auth */
        $authOptions = new ClientOptions(Environment::SANDBOX);
        $braspagAuthClient = new BraspagAuthClient($authOptions);

        $authRequest = new AccessTokenRequest();
        $authRequest->ClientId = "5d85902e-592a-44a9-80bb-bdda74d51bce";
        $authRequest->ClientSecret = "mddRzd6FqXujNLygC/KxOfhOiVhlUr2kjKPsOoYHwhQ=";
        $authRequest->GrantType = OAuthGrantType::ClientCredentials;
        $authRequest->Scope = "VelocityApp";

        /* Obtenção do token de acesso para serviço do Velocity Check */
        $authResponse = $braspagAuthClient->createAccessToken($authRequest);

        /* Criação do Cliente Velocity informando credencial de loja e token de acesso */
        $credentials = new MerchantCredentials("94E5EA52-79B0-7DBA-1867-BE7B081EDD97", $authResponse->Token);
        $velocityOptions = new VelocityClientOptions($credentials, Environment::SANDBOX); // Para produção, utilize Environment::PRODUCTION
        $velocityClient = new VelocityClient($velocityOptions);

        /* Analisando uma transação com Velocity Check */
        $analysisResponse = $this->PerformAnalysis($velocityClient);

        echo nl2br("Transaction analyzed\n");
        echo nl2br("Score: " . $analysisResponse->AnalysisResult->Score .  "\n");
        echo nl2br("Status: " . $analysisResponse->AnalysisResult->Status . "\n");
        echo nl2br("Accept By WhiteList: " . ($analysisResponse->AnalysisResult->AcceptByWhiteList ? "true" : "false") . "\n");
        echo nl2br("Reject By BlackList: " . ($analysisResponse->AnalysisResult->RejectByBlackList ? "true" : "false") . "\n");
    }

    private function PerformAnalysis(VelocityClient $velocityClient)
    {
        /* Preenchimento do objeto com os dados da análise */
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

        /* Execução da análise e retorno da resposta */
        return $velocityClient->performAnalysis($request);
    }
}

$demo = new VelocityDemo();
$demo->run();