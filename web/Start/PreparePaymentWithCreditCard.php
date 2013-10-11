<?php

include '../../src/Start/config.php';

use PayPal\Api\Address;
use PayPal\Api\Amount;
use PayPal\Api\CreditCard;
use PayPal\Api\Payer;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Transaction;

$storage = $registry->getStorageForClass($paypalRestPaymentDetailsClass, 'paypalRest');

$paymentDetails = $storage->createModel();
$storage->updateModel($paymentDetails);

$address = new Address();
$address->setLine1("3909 Witmer Road");
$address->setLine2("Niagara Falls");
$address->setCity("Niagara Falls");
$address->setState("NY");
$address->setPostal_code("14305");
$address->setCountry_code("US");
$address->setPhone("716-298-1822");

$card = new CreditCard();
$card->setType("visa");
$card->setNumber("4417119669820331");
$card->setExpire_month("11");
$card->setExpire_year("2019");
$card->setCvv2("012");
$card->setFirst_name("Joe");
$card->setLast_name("Shopper");
$card->setBilling_address($address);

$fi = new FundingInstrument();
$fi->setCredit_card($card);

$payer = new Payer();
$payer->setPayment_method("credit_card");
$payer->setFunding_instruments(array($fi));

$amount = new Amount();
$amount->setCurrency("USD");
$amount->setTotal("1.00");

$transaction = new Transaction();
$transaction->setAmount($amount);
$transaction->setDescription("This is the payment description.");

$paymentDetails->setIntent("sale");
$paymentDetails->setPayer($payer);
$paymentDetails->setTransactions(array($transaction));

$doneToken = $tokenStorage->createModel();
$doneToken->setPaymentName('paypalRest');
$doneToken->setDetails($storage->getIdentificator($paymentDetails));
$doneToken->setTargetUrl('http://'.$_SERVER['HTTP_HOST'].'/Start/done.php?payum_token='.$doneToken->getHash());
$tokenStorage->updateModel($doneToken);

$captureToken = $tokenStorage->createModel();
$captureToken->setPaymentName('paypalRest');
$captureToken->setDetails($storage->getIdentificator($paymentDetails));
$captureToken->setTargetUrl('http://'.$_SERVER['HTTP_HOST'].'/Start/capture.php?payum_token='.$captureToken->getHash());
$captureToken->setAfterUrl($doneToken->getTargetUrl());
$tokenStorage->updateModel($captureToken);

$storage->updateModel($paymentDetails);

header("Location: ".$captureToken->getTargetUrl());