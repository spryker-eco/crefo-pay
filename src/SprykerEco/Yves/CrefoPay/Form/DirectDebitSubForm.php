<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\CrefoPay\Form;

use Generated\Shared\Transfer\CrefoPayPaymentTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\AbstractSubFormType;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormProviderNameInterface;
use SprykerEco\Shared\CrefoPay\CrefoPayConfig;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DirectDebitSubForm extends AbstractSubFormType implements SubFormInterface, SubFormProviderNameInterface
{
    public const CREFO_PAY_SHOP_PUBLIC_KEY = 'shopPublicKey';
    public const CREFO_PAY_ORDER_ID = 'orderID';

    protected const PAYMENT_METHOD = 'direct-debit';

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
        return CrefoPayConfig::CREFO_PAY_PAYMENT_METHOD_DIRECT_DEBIT;
    }

    /**
     * @return string
     */
    public function getPropertyPath(): string
    {
        return CrefoPayConfig::CREFO_PAY_PAYMENT_METHOD_DIRECT_DEBIT;
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
            'data_class' => CrefoPayPaymentTransfer::class,
        ])->setRequired(static::OPTIONS_FIELD_NAME);
    }

    /**
     * @param \Symfony\Component\Form\FormView $view The view
     * @param \Symfony\Component\Form\FormInterface $form The form
     * @param array $options The options
     *
     * @return void
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        parent::buildView($view, $form, $options);
        $selectedOptions = $options[static::OPTIONS_FIELD_NAME];
        $view->vars[static::CREFO_PAY_SHOP_PUBLIC_KEY] = $selectedOptions[static::CREFO_PAY_SHOP_PUBLIC_KEY];
        $view->vars[static::CREFO_PAY_ORDER_ID] = $selectedOptions[static::CREFO_PAY_ORDER_ID];
    }
}
