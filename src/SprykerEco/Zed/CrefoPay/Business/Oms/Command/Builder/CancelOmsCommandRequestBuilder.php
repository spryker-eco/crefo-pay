<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Oms\Command\Builder;

use Generated\Shared\Transfer\CrefoPayApiCancelRequestTransfer;
use Generated\Shared\Transfer\CrefoPayApiRequestTransfer;
use Generated\Shared\Transfer\CrefoPayToSalesOrderItemCollectionTransfer;
use Generated\Shared\Transfer\OrderTransfer;

class CancelOmsCommandRequestBuilder implements CrefoPayOmsCommandRequestBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemCollectionTransfer $crefoPayToSalesOrderItemCollection
     *
     * @return \Generated\Shared\Transfer\CrefoPayApiRequestTransfer
     */
    public function buildRequestTransfer(
        OrderTransfer $orderTransfer,
        CrefoPayToSalesOrderItemCollectionTransfer $crefoPayToSalesOrderItemCollection
    ): CrefoPayApiRequestTransfer {
        return (new CrefoPayApiRequestTransfer())
            ->setCancelRequest();
    }

    protected function createCancelRequestTransfer(): CrefoPayApiCancelRequestTransfer
    {
        return (new CrefoPayApiCancelRequestTransfer());
    }
}
