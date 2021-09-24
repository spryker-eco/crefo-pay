<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\CrefoPay;

use ArrayObject;
use Codeception\Actor;
use Codeception\Scenario;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CrefoPayApiCreateTransactionResponseTransfer;
use Generated\Shared\Transfer\CrefoPayApiResponseTransfer;
use Generated\Shared\Transfer\CrefoPayNotificationTransfer;
use Generated\Shared\Transfer\CrefoPayTransactionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\TaxTotalTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayNotificationQuery;
use Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItemToCrefoPayNotificationQuery;
use SprykerEco\Zed\CrefoPay\CrefoPayConfig;
use SprykerEco\Zed\CrefoPay\Persistence\CrefoPayEntityManager;
use SprykerEco\Zed\CrefoPay\Persistence\CrefoPayEntityManagerInterface;
use SprykerEco\Zed\CrefoPay\Persistence\CrefoPayRepository;
use SprykerEco\Zed\CrefoPay\Persistence\CrefoPayRepositoryInterface;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class CrefoPayZedTester extends Actor
{
    use _generated\CrefoPayZedTesterActions;

    /**
     * @var array
     */
    protected const ALLOWED_PAYMENT_METHODS = ['PREPAID', 'CC', 'PAYPAL', 'SU', 'COD'];
    /**
     * @var array
     */
    protected const SPRYKER_PAYMENT_METHODS = [
        'crefoPayBill',
        'crefoPayCashOnDelivery',
        'crefoPayDirectDebit',
        'crefoPayPayPal',
        'crefoPayPrepaid',
        'crefoPaySofort',
        'crefoPayCreditCard',
        'crefoPayCreditCard3D',
    ];
    /**
     * @var int
     */
    protected const CREFO_PAY_API_LOG_ID = 123;
    /**
     * @var string
     */
    protected const RESPONSE_SALT = '1ee1cfcccd6051b2';
    /**
     * @var string
     */
    protected const CUSTOMER_REFERENCE = 'DE-22';
    /**
     * @var string
     */
    protected const CUSTOMER_NAME = 'John';
    /**
     * @var string
     */
    protected const CUSTOMER_SURNAME = 'Doe';
    /**
     * @var string
     */
    protected const CUSTOMER_EMAIL = 'john.doe@mail.com';
    /**
     * @var string
     */
    protected const CUSTOMER_SALUTATION = 'Mr';
    /**
     * @var string
     */
    protected const ADDRESS_STREET = 'Street';
    /**
     * @var string
     */
    protected const ADDRESS_NO = '130';
    /**
     * @var string
     */
    protected const ADDRESS_ADDITIONAL = 'Additional';
    /**
     * @var string
     */
    protected const ADDRESS_ZIP = '20537';
    /**
     * @var string
     */
    protected const ADDRESS_CITY = 'Hamburg';
    /**
     * @var string
     */
    protected const ADDRESS_COUNTRY = 'DE';
    /**
     * @var int
     */
    protected const TOTALS_PRICE_TO_PAY = 26772;
    /**
     * @var int
     */
    protected const TOTALS_TAX_RATE = 19;
    /**
     * @var int
     */
    protected const TOTALS_TAX_AMOUNT = 4275;
    /**
     * @var string
     */
    protected const ORDER_ID = 'DE--22-5c9098f724af27.85915877';
    /**
     * @var string
     */
    protected const USER_ID = 'DE--22';
    /**
     * @var string
     */
    protected const CURRENCY = 'EUR';
    /**
     * @var string
     */
    protected const TRANSACTION_STATUS = 'MERCHANTPENDING';
    /**
     * @var string
     */
    protected const TIMESTAMP = '1553159430402';
    /**
     * @var string
     */
    protected const API_VERSION = '2.1';
    /**
     * @var string
     */
    protected const REQUEST_MAC = '8a02ab4f5a8ad805e53a38452009c9deaf96976b';
    /**
     * @var string
     */
    protected const STATE_MACHINE_PROCESS_NAME = 'CrefoPayCreditCard01';
    /**
     * @var string
     */
    protected const PAYMENT_PROVIDER = 'CrefoPay';
    /**
     * @var string
     */
    protected const PAYMENT_METHOD = 'crefoPayCreditCard';

    /**
     * @param \Codeception\Scenario $scenario
     */
    public function __construct(Scenario $scenario)
    {
        parent::__construct($scenario);
        $this->setUpConfig();
    }

    /**
     * @return void
     */
    public function setUpConfig(): void
    {
        $this->setConfig('CREFO_PAY:MERCHANT_ID', 123);
        $this->setConfig('CREFO_PAY:STORE_ID', 'test');
        $this->setConfig('CREFO_PAY:IS_BUSINESS_TO_BUSINESS', false);
        $this->setConfig('ACTIVE_PROCESSES', ['CrefoPayCreditCard01']);
        $this->setConfig('PAYMENT_METHOD_STATEMACHINE_MAPPING', ['crefoPayCreditCard' => 'CrefoPayCreditCard01']);
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\CrefoPayConfig
     */
    public function createConfig(): CrefoPayConfig
    {
        return new CrefoPayConfig();
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Persistence\CrefoPayRepositoryInterface
     */
    public function createRepository(): CrefoPayRepositoryInterface
    {
        return new CrefoPayRepository();
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Persistence\CrefoPayEntityManagerInterface
     */
    public function createEntityManager(): CrefoPayEntityManagerInterface
    {
        return new CrefoPayEntityManager();
    }

    /**
     * @return \Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayNotificationQuery
     */
    public function createPaymentCrefoPayNotificationQuery(): SpyPaymentCrefoPayNotificationQuery
    {
        return SpyPaymentCrefoPayNotificationQuery::create();
    }

    /**
     * @return \Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItemToCrefoPayNotificationQuery
     */
    public function createPaymentCrefoPayOrderItemToCrefoPayNotificationQuery(): SpyPaymentCrefoPayOrderItemToCrefoPayNotificationQuery
    {
        return SpyPaymentCrefoPayOrderItemToCrefoPayNotificationQuery::create();
    }

    /**
     * @return void
     */
    public function createCrefoPayEntities(): void
    {
        $this->haveCrefoPayEntities(
            $this->createQuoteTransfer(),
            $this->createOrder()
        );
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransfer(): QuoteTransfer
    {
        return (new QuoteTransfer())
            ->setPayment($this->createPaymentTransfer())
            ->setCustomerReference(static::CUSTOMER_REFERENCE)
            ->setCustomer($this->createCustomerTransfer())
            ->setBillingAddress($this->createAddressTransfer())
            ->setShippingAddress($this->createAddressTransfer())
            ->setTotals($this->createTotalsTransfer())
            ->setCrefoPayTransaction($this->createCrefoPayTransactionTransfer());
    }

    /**
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    public function createPaymentTransfer(): PaymentTransfer
    {
        return (new PaymentTransfer())
            ->setPaymentProvider(static::PAYMENT_PROVIDER)
            ->setPaymentMethod(static::PAYMENT_METHOD)
            ->setPaymentSelection(static::PAYMENT_METHOD);
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function createCustomerTransfer(): CustomerTransfer
    {
        return (new CustomerTransfer())
            ->setIsGuest(false)
            ->setCustomerReference(static::CUSTOMER_REFERENCE)
            ->setEmail(static::CUSTOMER_EMAIL)
            ->setFirstName(static::CUSTOMER_NAME)
            ->setLastName(static::CUSTOMER_SURNAME)
            ->setSalutation(static::CUSTOMER_SALUTATION);
    }

    /**
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function createAddressTransfer(): AddressTransfer
    {
        return (new AddressTransfer())
            ->setLastName(static::CUSTOMER_NAME)
            ->setLastName(static::CUSTOMER_SURNAME)
            ->setSalutation(static::CUSTOMER_SALUTATION)
            ->setAddress1(static::ADDRESS_STREET)
            ->setAddress2(static::ADDRESS_NO)
            ->setAddress3(static::ADDRESS_ADDITIONAL)
            ->setZipCode(static::ADDRESS_ZIP)
            ->setCity(static::ADDRESS_CITY)
            ->setIso2Code(static::ADDRESS_COUNTRY);
    }

    /**
     * @return \Generated\Shared\Transfer\TotalsTransfer
     */
    public function createTotalsTransfer(): TotalsTransfer
    {
        return (new TotalsTransfer())
            ->setPriceToPay(static::TOTALS_PRICE_TO_PAY)
            ->setTaxTotal($this->createTaxTotalTransfer());
    }

    /**
     * @return \Generated\Shared\Transfer\TaxTotalTransfer
     */
    protected function createTaxTotalTransfer(): TaxTotalTransfer
    {
        return (new TaxTotalTransfer())
            ->setTaxRate(static::TOTALS_TAX_RATE)
            ->setAmount(static::TOTALS_TAX_AMOUNT);
    }

    /**
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function createPaymentMethodsTransfer(): PaymentMethodsTransfer
    {
        $paymentMethods = new ArrayObject();
        foreach (static::SPRYKER_PAYMENT_METHODS as $paymentMethod) {
            $paymentMethods->append(
                (new PaymentMethodTransfer())
                    ->setMethodName($paymentMethod)
            );
        }

        return (new PaymentMethodsTransfer())
            ->setMethods($paymentMethods);
    }

    /**
     * @return \Generated\Shared\Transfer\CrefoPayNotificationTransfer
     */
    public function createCrefoPayNotificationTransfer(): CrefoPayNotificationTransfer
    {
        return (new CrefoPayNotificationTransfer())
            ->setOrderID(static::ORDER_ID)
            ->setCaptureID('')
            ->setUserID(static::USER_ID)
            ->setAmount(static::TOTALS_PRICE_TO_PAY)
            ->setCurrency(static::CURRENCY)
            ->setTransactionStatus(static::TRANSACTION_STATUS)
            ->setTimestamp(static::TIMESTAMP)
            ->setVersion(static::API_VERSION)
            ->setMac(static::REQUEST_MAC);
    }

    /**
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function createSaveOrderTransfer(): SaveOrderTransfer
    {
        return new SaveOrderTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function createCheckoutResponseTransfer(): CheckoutResponseTransfer
    {
        return new CheckoutResponseTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\CrefoPayApiResponseTransfer
     */
    public function createCrefoPayApiResponseTransfer(): CrefoPayApiResponseTransfer
    {
        return (new CrefoPayApiResponseTransfer())
            ->setIsSuccess(true)
            ->setCrefoPayApiLogId(static::CREFO_PAY_API_LOG_ID)
            ->setCreateTransactionResponse($this->createCrefoPayApiCreateTransactionResponseTransfer());
    }

    /**
     * @return \Generated\Shared\Transfer\CrefoPayApiCreateTransactionResponseTransfer
     */
    public function createCrefoPayApiCreateTransactionResponseTransfer(): CrefoPayApiCreateTransactionResponseTransfer
    {
        return (new CrefoPayApiCreateTransactionResponseTransfer())
            ->setResultCode(0)
            ->setAllowedPaymentMethods(static::ALLOWED_PAYMENT_METHODS)
            ->setErrorDetails(new ArrayObject())
            ->setSalt(static::RESPONSE_SALT);
    }

    /**
     * @return \Generated\Shared\Transfer\CrefoPayTransactionTransfer
     */
    public function createCrefoPayTransactionTransfer(): CrefoPayTransactionTransfer
    {
        return (new CrefoPayTransactionTransfer())
            ->setAllowedPaymentMethods(static::ALLOWED_PAYMENT_METHODS);
    }

    /**
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function createOrder(): SaveOrderTransfer
    {
        return $this->haveOrder(
            [
                'unitPrice' => static::TOTALS_PRICE_TO_PAY,
                'sumPrice' => static::TOTALS_PRICE_TO_PAY,
            ],
            static::STATE_MACHINE_PROCESS_NAME
        );
    }
}
