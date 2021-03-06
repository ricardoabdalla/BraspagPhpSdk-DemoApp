<?php

require ("../vendor/autoload.php");

use BraspagSdk\Common\Environment;
use BraspagSdk\Pagador\PagadorClient;
use BraspagSdk\Pagador\PagadorClientOptions;
use BraspagSdk\Contracts\Pagador\CaptureRequest;
use BraspagSdk\Contracts\Pagador\CreditCardData;
use BraspagSdk\Contracts\Pagador\CustomerData;
use BraspagSdk\Contracts\Pagador\MerchantCredentials;
use BraspagSdk\Contracts\Pagador\PaymentDataRequest;
use BraspagSdk\Contracts\Pagador\SaleRequest;
use BraspagSdk\Contracts\Pagador\VoidRequest;

/* Exemplo de código para geração de vendas com cartão de crédito no gateway Pagador */
/* Para maiores informações, consulte a documentação do produto em https://braspag.github.io/manual/braspag-pagador */
class PagadorCreditCardDemo
{
    public function run()
    {
        echo nl2br("PAGADOR CRÉDITO\n");
        echo nl2br("=====================================\n");

        /* Criação do Cliente Pagador */
        $credentials = new MerchantCredentials("33B6AC07-C48D-4F13-A5B9-D3516A378A0C", "d6Rb3OParKvLfzNrURzwcT0f1lzNazS1o19yP6Y8");
        $options = new PagadorClientOptions($credentials, Environment::SANDBOX); // Para produção, utilize Environment::PRODUCTION
        $pagadorClient = new PagadorClient($options);

        /* Autorização */
        $sale = $this->createCreditCardSale($pagadorClient);

        echo nl2br("Transaction authorized\n");
        echo nl2br("Order ID: " . $sale->MerchantOrderId . "\n");
        echo nl2br("Payment ID: " . $sale->Payment->PaymentId . "\n");
        echo nl2br("Payment Status: " . $sale->Payment->Status . "\n");
        echo nl2br("\n");

        /* Captura */
        $saleCapture = $this->capture($sale->Payment->PaymentId, $pagadorClient);

        echo nl2br("Transaction captured\n");
        echo nl2br("Payment Status: " . $saleCapture->Status . "\n");
        echo nl2br("\n");

        /* Cancelamento */
        $saleVoid = $this->void($sale->Payment->PaymentId, $pagadorClient);

        echo nl2br("Transaction voided\n");
        echo nl2br("Payment Status: " . $saleVoid->Status . "\n");
        echo nl2br("\n");

        /* Recuperação da Transação */
        $saleRemote = $this->get($sale->Payment->PaymentId, $pagadorClient);

        echo nl2br("Transaction obtained from server\n");
        echo nl2br("Order ID: " . $saleRemote->MerchantOrderId . "\n");
        echo nl2br("Payment ID: " . $saleRemote->Payment->PaymentId . "\n");
        echo nl2br("Payment Status: " . $saleRemote->Payment->Status . "\n");
        echo nl2br("\n");
    }

    private function createCreditCardSale(PagadorClient $client)
    {
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

        $request->Payment->CreditCard = new CreditCardData();
        $request->Payment->CreditCard->CardNumber = "4485623136297301";
        $request->Payment->CreditCard->Holder = "BJORN IRONSIDE";
        $request->Payment->CreditCard->ExpirationDate = "12/2025";
        $request->Payment->CreditCard->SecurityCode = "123";
        $request->Payment->CreditCard->Brand = "visa";

        return $client->createSale($request);
    }

    private function capture($paymentId, PagadorClient $client)
    {
        $request = new CaptureRequest();
        $request->Amount = 100000;
        $request->PaymentId = $paymentId;

        return $client->capture($request);
    }

    private function void($paymentId, PagadorClient $client)
    {
        $request = new VoidRequest();
        $request->Amount = 100000;
        $request->PaymentId = $paymentId;

        return $client->void($request);
    }

    private function get($paymentId, PagadorClient $client)
    {
        return $client->get($paymentId);
    }
}

$demo = new PagadorCreditCardDemo();
$demo->run();