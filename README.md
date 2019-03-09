# Braspag SDK para PHP

Este repositório contém **exemplos de utilização** do SDK PHP [\(braspag/braspag-php-sdk\)]((https://github.com/ricardoabdalla/BraspagPhpSdk)) para integração simplificada nos serviços da plataforma [Braspag](https://www.braspag.com.br).

## Instalação

* É necessário ter o composer instalado em seu sistema. Para informações sobre instalação, consulte o site do [Composer](https://getcomposer.org/).

* No terminal, navegue para a pasta que contém o arquivo ``composer.json`` e execute o comando abaixo:

```
composer install
```

## Exemplos de utilização

* [PagadorCreditCardDemo.php](/examples/PagadorCreditCardDemo.php): exemplos de autorização, captura, cancelamento e recuperação de transações com cartão de crédito no gateway Pagador.
* [PagadorDebitCardDemo.php](/examples/PagadorDebitCardDemo.php): exemplos de autorização e recuperação de transações com cartão de débito no gateway Pagador.
* [PagadorBoletoDemo.php](/examples/PagadorBoletoDemo.php): exemplos de geração e recuperação de transações com boleto no gateway Pagador.
* [CartaoProtegidoDemo.php](/examples/CartaoProtegidoDemo.php): exemplos de salvamento e recuperação dos dados de cartão de crédito no cofre PCI Cartão Protegido.
* [VelocityDemo.php](/examples/VelocityDemo.php): exemplos de análise de uma transação de crédito no Velocity Check.
