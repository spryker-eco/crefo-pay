<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Mapper\PaymentMethod;

interface CrefoPayPaymentMethodMapperInterface
{
    /**
     * @param string $internalPaymentMethodName
     *
     * @return string|null
     */
    public function mapInternalToExternalPaymentMethodName(string $internalPaymentMethodName): ?string;
}
