<?php

require ("vendor/autoload.php");

use BraspagSdk\Common\Environment;
use BraspagSdk\Contracts\Pagador\CustomerData;
use BraspagSdk\Contracts\Pagador\DebitCardData;
use BraspagSdk\Contracts\Pagador\MerchantCredentials;
use BraspagSdk\Contracts\Pagador\PaymentDataRequest;
use BraspagSdk\Contracts\Pagador\SaleRequest;
use BraspagSdk\Contracts\Pagador\VoidRequest;
use BraspagSdk\Pagador\PagadorClient;
use BraspagSdk\Pagador\PagadorClientOptions;

// Exemplo de teste para gerar venda com cartão de débito
class PagadorDebitCardDemo
{
    public function run()
    {
        echo "PAGADOR DÉBITO\n";
        echo "=====================================\n";

        /* Criação do Cliente Pagador */
        $credentials = new MerchantCredentials("33B6AC07-C48D-4F13-A5B9-D3516A378A0C", "d6Rb3OParKvLfzNrURzwcT0f1lzNazS1o19yP6Y8");
        $options = new PagadorClientOptions($credentials, Environment::SANDBOX);
        $pagadorClient = new PagadorClient($options);

        /* Efetivação da transação */
        $sale = $this->createDebitCardSale($pagadorClient);

        echo "Transaction authorized\n";
        echo "Order ID: " . $sale->MerchantOrderId . "\n";
        echo "Payment ID: " . $sale->Payment->PaymentId . "\n";
        echo "Payment Status: " . $sale->Payment->Status . "\n";
        echo "URL de autenticação:" . $sale->Payment->AuthenticationUrl . "\n";
        echo "\n";

        /* Recuperação da Transação */
        $saleRemote = $this->get($sale->Payment->PaymentId, $pagadorClient);

        echo "Transaction obtained from server\n";
        echo "Order ID: " . $saleRemote->MerchantOrderId . "\n";
        echo "Payment ID: " . $saleRemote->Payment->PaymentId . "\n";
        echo "Payment Status: " . $saleRemote->Payment->Status . "\n";
        echo "URL de autenticação:" . $sale->Payment->AuthenticationUrl . "\n";
        echo "\n";
    }

    private function createDebitCardSale(PagadorClient $client)
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
        $request->Payment->Type = "DebitCard";
        $request->Payment->Currency = "BRL";
        $request->Payment->Country = "BRA";
        $request->Payment->Amount = 150000;
        $request->Payment->Installments = 1;
        $request->Payment->SoftDescriptor = "Braspag SDK";
        $request->Payment->ReturnUrl = "http://www.sualoja.com/url-de-retorno";
        $request->Payment->Authenticate = true;

        $request->Payment->DebitCard = new DebitCardData();
        $request->Payment->DebitCard->CardNumber = "4485623136297301";
        $request->Payment->DebitCard->Holder = "BJORN IRONSIDE";
        $request->Payment->DebitCard->ExpirationDate = "12/2025";
        $request->Payment->DebitCard->SecurityCode = "123";
        $request->Payment->DebitCard->Brand = "Visa";

        return $client->createSale($request);
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

$demo = new PagadorDebitCardDemo();
$demo->run();