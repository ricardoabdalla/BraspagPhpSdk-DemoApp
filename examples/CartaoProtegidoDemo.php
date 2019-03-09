<?php

require ("../vendor/autoload.php");

use BraspagSdk\CartaoProtegido\CartaoProtegidoClient;
use BraspagSdk\CartaoProtegido\CartaoProtegidoClientOptions;
use BraspagSdk\Common\Environment;
use BraspagSdk\Common\Utilities;
use BraspagSdk\Contracts\CartaoProtegido\GetCreditCardRequest;
use BraspagSdk\Contracts\CartaoProtegido\MerchantCredentials;
use BraspagSdk\Contracts\CartaoProtegido\SaveCreditCardRequest;

/* Exemplo de código para armazenamento seguro de cartões no cofre PCI Cartão Protegido */
/* Para maiores informações, consulte a documentação do produto em https://braspag.github.io/tutorial/braspag-cartao-protegido */
class CartaoProtegidoDemo
{
    public function run()
    {
        echo nl2br("CARTAO PROTEGIDO\n");
        echo nl2br("=====================================\n");

        /* Criação do Cliente Cartão Protegido */
        $credentials = new MerchantCredentials("106c8a0c-89a4-4063-bf50-9e6c8530593b");
        $options = new CartaoProtegidoClientOptions($credentials, Environment::SANDBOX); // Para produção, utilize Environment::PRODUCTION
        $cartaoProtegidoClient = new CartaoProtegidoClient($options);

        /* Salvar cartão */
        $saveCreditCardResponse = $this->SaveCreditCard($cartaoProtegidoClient);

        echo nl2br("Card tokenized\n");
        echo nl2br("Card token: " . $saveCreditCardResponse->JustClickKey . "\n");
        echo nl2br("\n");

        /* Recuperação dos dados de cartão via token */
        $getCreditCardResponse = $this->GetCreditCard($cartaoProtegidoClient, $saveCreditCardResponse->JustClickKey);

        echo nl2br("Card data obtained from secure server\n");
        echo nl2br("Card Number: " . $getCreditCardResponse->CardNumber . "\n");
        echo nl2br("Card Holder: " . $getCreditCardResponse->CardHolder . "\n");
        echo nl2br("Card Expiration: " . $getCreditCardResponse->CardExpiration . "\n");
        echo nl2br("\n");
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