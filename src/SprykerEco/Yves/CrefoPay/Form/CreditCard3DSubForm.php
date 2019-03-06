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
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreditCard3DSubForm extends AbstractSubFormType implements SubFormInterface, SubFormProviderNameInterface
{
    public const CREFO_PAY_SHOP_PUBLIC_KEY = 'shopPublicKey';
    public const CREFO_PAY_ORDER_ID = 'orderID';
    public const CREFO_PAY_SECURE_FIELDS_API_ENDPOINT = 'secureFieldsApiEndpoint';
    public const CREFO_PAY_SECURE_FIELDS_LIBRARY_URL = 'secureFieldsLibraryUrl';
    public const CREFO_PAY_SECURE_FIELDS_PLACEHOLDERS = 'secureFieldsPlaceholders';

    protected const PAYMENT_METHOD = 'credit-card-3d';
    protected const FORM_FIELD_PAYMENT_METHOD = 'paymentMethod';
    protected const FORM_FIELD_PAYMENT_METHOD_DATA = 'CC3D';
    protected const FORM_FIELD_ATTRIBUTE_DATA_CREFO_PAY_NAME = 'data-crefopay';
    protected const FORM_FIELD_ATTRIBUTE_DATA_CREFO_PAY_VALUE = 'paymentMethod';
    protected const FORM_FIELD_PAYMENT_INSTRUMENT_ID = 'paymentInstrumentId';

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
        return CrefoPayConfig::CREFO_PAY_PAYMENT_METHOD_CREDIT_CARD_3D;
    }

    /**
     * @return string
     */
    public function getPropertyPath(): string
    {
        return CrefoPayConfig::CREFO_PAY_PAYMENT_METHOD_CREDIT_CARD_3D;
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
        $view->vars[static::CREFO_PAY_SECURE_FIELDS_API_ENDPOINT] = $selectedOptions[static::CREFO_PAY_SECURE_FIELDS_API_ENDPOINT];
        $view->vars[static::CREFO_PAY_SECURE_FIELDS_PLACEHOLDERS] = $selectedOptions[static::CREFO_PAY_SECURE_FIELDS_PLACEHOLDERS];
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addPaymentMethod($builder)
            ->addPaymentInstrumentId($builder);
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
            HiddenType::class,
            [
                'label' => false,
                'data' => static::FORM_FIELD_PAYMENT_METHOD_DATA,
                'attr' => [
                    static::FORM_FIELD_ATTRIBUTE_DATA_CREFO_PAY_NAME => static::FORM_FIELD_ATTRIBUTE_DATA_CREFO_PAY_VALUE,
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPaymentInstrumentId(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FORM_FIELD_PAYMENT_INSTRUMENT_ID,
            HiddenType::class,
            ['label' => false]
        );

        return $this;
    }
}
