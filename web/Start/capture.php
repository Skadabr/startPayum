<?php

use Payum\Request\BinaryMaskStatusRequest;
use Payum\Request\SecuredCaptureRequest;
use Payum\Request\RedirectUrlInteractiveRequest;

include '../../src/Start/config.php';

$token = $requestVerifier->verify($_REQUEST);
$payment = $registry->getPayment($token->getPaymentName());

$payment->execute($status = new BinaryMaskStatusRequest($token));
if (false == $status->isNew()) {
    header('HTTP/1.1 400 Bad Request', true, 400);
    exit;
}

if ($interactiveRequest = $payment->execute(new SecuredCaptureRequest($token), true)) {
    if ($interactiveRequest instanceof RedirectUrlInteractiveRequest) {
        header("Location: ".$interactiveRequest->getUrl());
        die();
    }

    throw new \LogicException('Unsupported interactive request', null, $interactiveRequest);
}

$requestVerifier->invalidate($token);

header("Location: ".$token->getAfterUrl());