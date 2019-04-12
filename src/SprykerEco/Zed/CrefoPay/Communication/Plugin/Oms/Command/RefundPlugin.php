<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Communication\Plugin\Oms\Command;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByItemInterface;

/**
 * @method \SprykerEco\Zed\CrefoPay\CrefoPayConfig getConfig()
 * @method \SprykerEco\Zed\CrefoPay\Business\CrefoPayFacadeInterface getFacade()
 * @method \SprykerEco\Zed\CrefoPay\Communication\CrefoPayCommunicationFactory getFactory()
 *
 * @SuppressWarnings(PHPMD.NewPluginExtensionModuleRule)
 */
class RefundPlugin extends AbstractPlugin implements CommandByItemInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(SpySalesOrderItem $orderItem, ReadOnlyArrayObject $data)
    {
        $this->getFactory()
            ->createRefundOmsCommand()
            ->execute($orderItem, $data);

        return [];
    }
}
