<?php
/**
 * Created by PhpStorm.
 * User: skadabr
 * Date: 9/23/13
 * Time: 4:19 PM
 */

namespace Start\Model;

use Payum\Paypal\ExpressCheckout\Nvp\Model\PaymentDetails as BasePaymentDetails;

class PaypalPaymentDetails extends BasePaymentDetails
{
    protected $id;

}