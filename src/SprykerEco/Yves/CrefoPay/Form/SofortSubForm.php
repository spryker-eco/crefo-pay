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
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SofortSubForm extends AbstractSubFormType implements SubFormInterface, SubFormProviderNameInterface
{
    protected const PAYMENT_METHOD = 'sofort';
    protected const FORM_FIELD_PAYMENT_METHOD = 'paymentMethod';
    protected const FORM_FIELD_PAYMENT_METHOD_DATA = 'SU';
    protected const FORM_FIELD_PAYMENT_METHOD_CLASSES = 'crefopay-payment-method is_hidden';
    protected const FORM_FIELD_ATTRIBUTE_DATA_CREFO_PAY_NAME = 'data-crefopay';
    protected const FORM_FIELD_ATTRIBUTE_DATA_CREFO_PAY_VALUE = 'paymentMethod';

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
        return CrefoPayConfig::CREFO_PAY_PAYMENT_METHOD_SOFORT;
    }

    /**
     * @return string
     */
    public function getPropertyPath(): string
    {
        return CrefoPayConfig::CREFO_PAY_PAYMENT_METHOD_SOFORT;
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
            RadioType::class,
            [
                'label' => false,
                'value' => static::FORM_FIELD_PAYMENT_METHOD_DATA,
                'attr' => [
                    'class' => static::FORM_FIELD_PAYMENT_METHOD_CLASSES,
                    static::FORM_FIELD_ATTRIBUTE_DATA_CREFO_PAY_NAME => static::FORM_FIELD_ATTRIBUTE_DATA_CREFO_PAY_VALUE,
                ],
            ]
        );

        return $this;
    }
}
