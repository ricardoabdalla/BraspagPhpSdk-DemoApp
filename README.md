# Braspag SDK para PHP

Este repositório contém **exemplos de utilização** do SDK para integração simplificada nos serviços da plataforma Braspag, disponível em [https://github.com/ricardoabdalla/BraspagPhpSdk](https://github.com/ricardoabdalla/BraspagPhpSdk).

## Instalação

No terminal, navegue para a pasta que contém o arquivo ``composer.json`` e execute o comando abaixo:

```
composer install
```

> É necessário ter o composer instalado em seu sistema. Para mais informações, consulte [Composer](https://getcomposer.org/)

## Exemplos de utilização

* [PagadorCreditCardDemo.php](/PagadorCreditCardDemo.php): exemplos para operações com cartão de crédito no gateway Pagador.
* Endpoints Braspag já configurados no pacote
* Seleção de ambientes Sandbox ou Production
* Client para a API Braspag Auth (Obtenção de tokens de acesso)
* Client para a API de pagamentos Recorrentes
* Client para a API do Pagador (Autorização, Captura, Cancelamento/Estorno, Consulta)
* Client para a API do Cartão Protegido (Salvar cartão, Recuperar cartão, Invalidar cartão)
* Client para a API de análises do Velocity