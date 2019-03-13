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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PayPalSubForm extends AbstractSubFormType implements SubFormInterface, SubFormProviderNameInterface
{
    protected const PAYMENT_METHOD = 'paypal';
    protected const FORM_FIELD_PAYMENT_METHOD = 'paymentMethod';
    protected const FORM_FIELD_PAYMENT_METHOD_DATA = 'PAYPAL';

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
        return CrefoPayConfig::CREFO_PAY_PAYMENT_METHOD_PAY_PAL;
    }

    /**
     * @return string
     */
    public function getPropertyPath(): string
    {
        return CrefoPayConfig::CREFO_PAY_PAYMENT_METHOD_PAY_PAL;
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
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addPaymentMethod($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPaymentMethod(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FORM_FIELD_PAYMENT_METHOD,
            ChoiceType::class,
            [
                'choices' => [static::FORM_FIELD_PAYMENT_METHOD_DATA],
                'choices_as_values' => true,
                'expanded' => true,
            ]
        );

        return $this;
    }
}
