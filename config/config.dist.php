<?php
/**
 * Copy over the following configs to your config
 */

use SprykerEco\Shared\CrefoPay\CrefoPayConstants;
use SprykerEco\Shared\CrefoPayApi\CrefoPayApiConstants;

$config[CrefoPayConstants::MERCHANT_ID] = 1; //Merchant ID provided by CrefoPay int value.
$config[CrefoPayConstants::STORE_ID] = 'STOREID'; //Store ID provided by CrefoPay string value.
$config[CrefoPayConstants::REFUND_DESCRIPTION] = 'Refund was performed from Backend office.'; //Refund description that will be shown in merchant backend, required option.
$config[CrefoPayConstants::SECURE_FIELDS_API_ENDPOINT] = 'https://sandbox.crefopay.de/secureFields/'; //Change it to live version on production.
$config[CrefoPayConstants::IS_BUSINESS_TO_BUSINESS] = false; //Set to true in case of b2b model.
$config[CrefoPayConstants::CAPTURE_EXPENSES_SEPARATELY] = false; //Set to true to capture expenses with separate transaction.
$config[CrefoPayConstants::SECURE_FIELDS_PLACEHOLDERS] = [
    'accountHolder' => 'John Doe',
    'number' => '5555555555554444',
    'cvv' => '123',
    'iban' => 'DE06000000000023456789',
    'bic' => 'SFRTDE20000',
];

$config[CrefoPayApiConstants::CREATE_TRANSACTION_API_ENDPOINT] = 'https://sandbox.crefopay.de/2.0/createTransaction'; //Change it to live version on production.
$config[CrefoPayApiConstants::RESERVE_API_ENDPOINT] = 'https://sandbox.crefopay.de/2.0/reserve'; //Change it to live version on production.
$config[CrefoPayApiConstants::CAPTURE_API_ENDPOINT] = 'https://sandbox.crefopay.de/2.0/capture'; //Change it to live version on production.
$config[CrefoPayApiConstants::CANCEL_API_ENDPOINT] = 'https://sandbox.crefopay.de/2.0/cancel'; //Change it to live version on production.
$config[CrefoPayApiConstants::REFUND_API_ENDPOINT] = 'https://sandbox.crefopay.de/2.0/refund'; //Change it to live version on production.
$config[CrefoPayApiConstants::FINISH_API_ENDPOINT] = 'https://sandbox.crefopay.de/2.0/finish'; //Change it to live version on production.

$config[CrefoPayApiConstants::PRIVATE_KEY] = 'PRIVATEKEY'; //Private key provided by CrefoPay string value. Used for MAC calculation.
$config[CrefoPayApiConstants::PUBLIC_KEY] = 'PUBLICKEY'; //Public key provided by CrefoPay string value. Used for sign requests.
