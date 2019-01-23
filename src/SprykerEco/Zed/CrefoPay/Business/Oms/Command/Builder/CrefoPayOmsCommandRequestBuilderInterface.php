<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Oms\Command\Builder;

use Generated\Shared\Transfer\CrefoPayApiRequestTransfer;
use Generated\Shared\Transfer\CrefoPayToSalesOrderItemCollectionTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayTransfer;

interface CrefoPayOmsCommandRequestBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentCrefoPayTransfer $paymentCrefoPayTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemCollectionTransfer $crefoPayToSalesOrderItemCollection
     *
     * @return \Generated\Shared\Transfer\CrefoPayApiRequestTransfer
     */
    public function buildRequestTransfer(
        PaymentCrefoPayTransfer $paymentCrefoPayTransfer,
        OrderTransfer $orderTransfer,
        CrefoPayToSalesOrderItemCollectionTransfer $crefoPayToSalesOrderItemCollection
    ): CrefoPayApiRequestTransfer;
}
