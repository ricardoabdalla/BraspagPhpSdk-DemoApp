<?php

require ("vendor/autoload.php");

use BraspagSdk\CartaoProtegido\CartaoProtegidoClient;
use BraspagSdk\CartaoProtegido\CartaoProtegidoClientOptions;
use BraspagSdk\Common\Environment;
use BraspagSdk\Common\Utilities;
use BraspagSdk\Contracts\CartaoProtegido\GetCreditCardRequest;
use BraspagSdk\Contracts\CartaoProtegido\MerchantCredentials;
use BraspagSdk\Contracts\CartaoProtegido\SaveCreditCardRequest;

class CartaoProtegidoDemo
{
    public function run()
    {
        echo "CARTAO PROTEGIDO\n";
        echo "=====================================\n";

        /* Criação do Cliente Cartão Protegido */
        $credentials = new MerchantCredentials("106c8a0c-89a4-4063-bf50-9e6c8530593b");
        $options = new CartaoProtegidoClientOptions($credentials, Environment::SANDBOX);
        $cartaoProtegidoClient = new CartaoProtegidoClient($options);

        /* Salvar cartão */
        $saveCreditCardResponse = $this->SaveCreditCard($cartaoProtegidoClient);

        echo "Card tokenized\n";
        echo "Card token: " . $saveCreditCardResponse->JustClickKey .  "\n";
        echo "\n";

        /* Recuperação dos dados de cartão via token */
        $getCreditCardResponse = $this->GetCreditCard($cartaoProtegidoClient, $saveCreditCardResponse->JustClickKey);



        echo "Card data obtained from secure server\n";
        echo "Card Number: " . $getCreditCardResponse->CardNumber . "\n";
        echo "Card Holder: " . $getCreditCardResponse->CardHolder .  "\n";
        echo "Card Expiration: " . $getCreditCardResponse->CardExpiration . "\n";
        echo "\n";
    }

    private function SaveCreditCard(CartaoProtegidoClient $client)
    {
        $request = new SaveCreditCardRequest();

        $request->CustomerName = "Bjorn Ironside";
        $request->CustomerIdentification = "762.502.520-96";
        $request->CardHolder = "BJORN IRONSIDE";
        $request->CardExpiration = "10/2025";
        $request->CardNumber = "1000100010001000";
        $request->JustClickAlias = uniqid();

        return $client->saveCreditCard($request);
    }

    private function GetCreditCard(CartaoProtegidoClient $client, $token)
    {
        $request = new GetCreditCardRequest();

        $request->JustClickKey = $token;
        $request->RequestId = Utilities::getGUID();

        return $client->getCreditCard($request);
    }
}

$demo = new CartaoProtegidoDemo();
$demo->run();