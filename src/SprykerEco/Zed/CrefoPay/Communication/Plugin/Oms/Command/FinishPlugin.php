<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Communication\Plugin\Oms\Command;

use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;

/**
 * @method \SprykerEco\Zed\CrefoPay\CrefoPayConfig getConfig()
 * @method \SprykerEco\Zed\CrefoPay\Business\CrefoPayFacadeInterface getFacade()
 * @method \SprykerEco\Zed\CrefoPay\Communication\CrefoPayCommunicationFactory getFactory()
 *
 * @SuppressWarnings(PHPMD.NewPluginExtensionModuleRule)
 */
class FinishPlugin extends AbstractPlugin implements CommandByOrderInterface
{
    /**
     * {@inheritDoc}
     * - Makes finish request to CrefoPay API.
     * - Updates order items status.
     *
     * @api
     *
     * @param array<\Orm\Zed\Sales\Persistence\SpySalesOrderItem> $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data): array
    {
        $this->getFactory()
            ->createFinishOmsCommand()
            ->execute($orderItems, $orderEntity, $data);

        return [];
    }
}
