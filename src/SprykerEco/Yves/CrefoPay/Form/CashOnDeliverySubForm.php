<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\CrefoPay\Form;

use Generated\Shared\Transfer\CrefoPayCashOnDeliveryPaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\AbstractSubFormType;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormProviderNameInterface;
use SprykerEco\Shared\CrefoPay\CrefoPayConfig;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CashOnDeliverySubForm extends AbstractSubFormType implements SubFormInterface, SubFormProviderNameInterface
{
    protected const PAYMENT_METHOD = 'cash-on-delivery';

    /**
     * @return string
     */
    public function getProviderName(): string
    {
        return CrefoPayConfig::PROVIDER_NAME;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return PaymentTransfer::CREFO_PAY_CASH_ON_DELIVERY;
    }

    /**
     * @return string
     */
    public function getPropertyPath(): string
    {
        return PaymentTransfer::CREFO_PAY_CASH_ON_DELIVERY;
    }

    /**
     * @return string
     */
    public function getTemplatePath(): string
    {
        return CrefoPayConfig::PROVIDER_NAME . DIRECTORY_SEPARATOR . static::PAYMENT_METHOD;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CrefoPayCashOnDeliveryPaymentTransfer::class,
        ])->setRequired(static::OPTIONS_FIELD_NAME);
    }
}
