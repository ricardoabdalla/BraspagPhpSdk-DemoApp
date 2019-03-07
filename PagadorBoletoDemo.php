<?php

use BraspagSdk\Common\Environment;
use BraspagSdk\Contracts\Pagador\CustomerData;
use BraspagSdk\Contracts\Pagador\MerchantCredentials;
use BraspagSdk\Contracts\Pagador\PaymentDataRequest;
use BraspagSdk\Contracts\Pagador\SaleRequest;
use BraspagSdk\Pagador\PagadorClient;
use BraspagSdk\Pagador\PagadorClientOptions;

require ("vendor/autoload.php");

// Exemplo de teste para gerar boleto
class PagadorBoletoDemo
{
    public function run()
    {
        echo "PAGADOR BOLETO\n";
        echo "=====================================\n";

        /* Criação do Cliente Pagador */
        $credentials = new MerchantCredentials("33B6AC07-C48D-4F13-A5B9-D3516A378A0C", "d6Rb3OParKvLfzNrURzwcT0f1lzNazS1o19yP6Y8");
        $options = new PagadorClientOptions($credentials, Environment::SANDBOX);
        $pagadorClient = new PagadorClient($options);

        /* Geração do boleto */
        $sale = $this->createBoletoSale($pagadorClient);

        echo "Transaction authorized\n";
        echo "Order ID: " . $sale->MerchantOrderId . "\n";
        echo "Payment ID: " . $sale->Payment->PaymentId . "\n";
        echo "Payment Status: " . $sale->Payment->Status . "\n";
        echo "Boleto URL: " . $sale->Payment->Url . "\n";
        echo "Barcode: " . $sale->Payment->BarCodeNumber . "\n";
        echo "\n";

        /* Recuperação da Transação */
        $saleRemote = $this->get($sale->Payment->PaymentId, $pagadorClient);

        echo "Transaction obtained from server\n";
        echo "Order ID: " . $saleRemote->MerchantOrderId . "\n";
        echo "Payment ID: " . $saleRemote->Payment->PaymentId . "\n";
        echo "Payment Status: " . $saleRemote->Payment->Status . "\n";
        echo "Boleto URL: " . $saleRemote->Payment->Url . "\n";
        echo "Barcode: " . $saleRemote->Payment->BarCodeNumber . "\n";
        echo "\n";
    }

    private function createBoletoSale(PagadorClient $client)
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
        $request->Payment->Type = "Boleto";
        $request->Payment->Currency = "BRL";
        $request->Payment->Country = "BRA";
        $request->Payment->Amount = 150000;
        $request->Payment->BoletoNumber = "2017091101";
        $request->Payment->Assignor = "Braspag";
        $request->Payment->Demonstrative = "Texto demonstrativo";
        $request->Payment->ExpirationDate = "2019-03-20";
        $request->Payment->Identification = "11017523000167";
        $request->Payment->Instructions = "Aceitar somente até a data de vencimento.";

        return $client->createSale($request);
    }

    private function get($paymentId, PagadorClient $client)
    {
        return $client->get($paymentId);
    }
}

$demo = new PagadorBoletoDemo();
$demo->run();