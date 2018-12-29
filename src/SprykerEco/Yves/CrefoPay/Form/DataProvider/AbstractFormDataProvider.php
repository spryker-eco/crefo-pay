<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\CrefoPay\Form\DataProvider;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use SprykerEco\Yves\CrefoPay\Dependency\Client\CrefoPayToQuoteClientInterface;

abstract class AbstractFormDataProvider implements StepEngineFormDataProviderInterface
{
    /**
     * @var \SprykerEco\Yves\CrefoPay\Dependency\Client\CrefoPayToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @param \SprykerEco\Yves\CrefoPay\Dependency\Client\CrefoPayToQuoteClientInterface $quoteClient
     */
    public function __construct(CrefoPayToQuoteClientInterface $quoteClient)
    {
        $this->quoteClient = $quoteClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions(AbstractTransfer $quoteTransfer): array
    {
        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function updateQuoteWithPaymentData(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $paymentTransfer = $quoteTransfer->getPayment();

        if ($paymentTransfer === null) {
            $paymentTransfer = new PaymentTransfer();
            $quoteTransfer->setPayment($paymentTransfer);
        }

        return $quoteTransfer;
    }
}
