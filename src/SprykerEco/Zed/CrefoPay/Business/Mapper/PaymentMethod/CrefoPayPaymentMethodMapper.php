<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Mapper\PaymentMethod;

use SprykerEco\Zed\CrefoPay\CrefoPayConfig;

class CrefoPayPaymentMethodMapper implements CrefoPayPaymentMethodMapperInterface
{
    /**
     * @var \SprykerEco\Zed\CrefoPay\CrefoPayConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Zed\CrefoPay\CrefoPayConfig $config
     */
    public function __construct(CrefoPayConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $internalPaymentMethodName
     *
     * @return string|null
     */
    public function mapInternalToExternalPaymentMethodName(string $internalPaymentMethodName): ?string
    {
        $paymentMethodNames = $this->getInternalToExternalPaymentMethodNamesMapping();

        if (!isset($paymentMethodNames[$internalPaymentMethodName])) {
            return null;
        }

        return $paymentMethodNames[$internalPaymentMethodName];
    }

    /**
     * @return array<string>
     */
    protected function getInternalToExternalPaymentMethodNamesMapping(): array
    {
        return $this->config->getInternalToExternalPaymentMethodNamesMapping();
    }
}
