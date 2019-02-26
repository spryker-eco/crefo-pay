<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Shared\CrefoPay;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface CrefoPayConstants
{
    public const AUTO_CAPTURE = 'CREFO_PAY:AUTO_CAPTURE';
    public const MERCHANT_ID = 'CREFO_PAY:MERCHANT_ID';
    public const STORE_ID = 'CREFO_PAY:STORE_ID';
    public const REFUND_DESCRIPTION = 'CREFO_PAY:REFUND_DESCRIPTION';
    public const REGISTER_PAYMENT_ACTION_URL = 'CREFO_PAY:REGISTER_PAYMENT_ACTION_URL';
    public const SECURE_FIELDS_LIBRARY_URL = 'CREFO_PAY:SECURE_FIELDS_LIBRARY_URL';
    public const SECURE_FIELDS_PLACEHOLDERS = 'CREFO_PAY:SECURE_FIELDS_PLACEHOLDERS';
}
