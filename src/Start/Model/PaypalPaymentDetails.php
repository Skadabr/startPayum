<?php

namespace Start\Model;

use Payum\Paypal\ExpressCheckout\Nvp\Model\PaymentDetails as BasePaymentDetails;

class PaypalPaymentDetails extends BasePaymentDetails
{
    protected $id;
}