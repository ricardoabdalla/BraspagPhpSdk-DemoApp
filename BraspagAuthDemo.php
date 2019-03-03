<?php

require ("vendor/autoload.php");

use BraspagSdk\BraspagAuth\BraspagAuthClient;
use BraspagSdk\Common\ClientOptions;
use BraspagSdk\Common\Environment;
use BraspagSdk\Common\OAuthGrantType;
use BraspagSdk\Contracts\BraspagAuth\AccessTokenRequest;

echo "BRASPAGAUTH\n";
echo "=====================================\n";

$options = new ClientOptions();
$options->Environment = Environment::SANDBOX;

$client = new BraspagAuthClient($options);

$request = new AccessTokenRequest();
$request->ClientId = "5d85902e-592a-44a9-80bb-bdda74d51bce";
$request->ClientSecret = "mddRzd6FqXujNLygC/KxOfhOiVhlUr2kjKPsOoYHwhQ=";
$request->GrantType = OAuthGrantType::ClientCredentials;
$request->Scope = "VelocityApp";

$response = $client->createAccessToken($request);

echo "Token: " . $response->Token . "\n";
echo "Http Status: " . $response->HttpStatus . "\n";
echo "Expira em: " . $response->ExpiresIn . "\n";
