<?php
namespace SprykerEcoTest\Zed\CrefoPay;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CrefoPayNotificationTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use SprykerEco\Zed\CrefoPay\CrefoPayConfig;

/**
 * Inherited Methods
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
class CrefoPayZedTester extends \Codeception\Actor
{
    use _generated\CrefoPayZedTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * @return \SprykerEco\Zed\CrefoPay\CrefoPayConfig
     */
    public function createConfig(): CrefoPayConfig
    {
        return new CrefoPayConfig();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransfer(): QuoteTransfer
    {
        return new QuoteTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function createPaymentMethodsTransfer(): PaymentMethodsTransfer
    {
        return new PaymentMethodsTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\CrefoPayNotificationTransfer
     */
    public function createCrefoPayNotificationTransfer(): CrefoPayNotificationTransfer
    {
        return new CrefoPayNotificationTransfer();
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
}
