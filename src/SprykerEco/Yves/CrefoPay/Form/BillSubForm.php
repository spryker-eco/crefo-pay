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
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BillSubForm extends AbstractSubFormType implements SubFormInterface, SubFormProviderNameInterface
{
    public const CREFO_PAY_SHOP_PUBLIC_KEY = 'shopPublicKey';
    public const CREFO_PAY_ORDER_ID = 'orderID';
    public const CREFO_PAY_SECURE_FIELDS_API_ENDPOINT = 'secureFieldsApiEndpoint';
    public const CREFO_PAY_SECURE_FIELDS_PLACEHOLDERS = 'secureFieldsPlaceholders';

    protected const PAYMENT_METHOD = 'bill';
    protected const FORM_FIELD_PAYMENT_METHOD = 'paymentMethod';
    protected const FORM_FIELD_PAYMENT_METHOD_DATA = 'BILL';

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
        return CrefoPayConfig::CREFO_PAY_PAYMENT_METHOD_BILL;
    }

    /**
     * @return string
     */
    public function getPropertyPath(): string
    {
        return CrefoPayConfig::CREFO_PAY_PAYMENT_METHOD_BILL;
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
