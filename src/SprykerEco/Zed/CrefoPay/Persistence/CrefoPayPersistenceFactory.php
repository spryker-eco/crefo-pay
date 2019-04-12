<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Persistence;

use Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayNotificationQuery;
use Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItemQuery;
use Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItemToCrefoPayApiLogQuery;
use Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItemToCrefoPayNotificationQuery;
use Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use SprykerEco\Zed\CrefoPay\Persistence\Mapper\CrefoPayPersistenceMapper;
use SprykerEco\Zed\CrefoPay\Persistence\Mapper\CrefoPayPersistenceMapperInterface;

/**
 * @method \SprykerEco\Zed\CrefoPay\CrefoPayConfig getConfig()
 * @method \SprykerEco\Zed\CrefoPay\Persistence\CrefoPayEntityManagerInterface getEntityManager()
 * @method \SprykerEco\Zed\CrefoPay\Persistence\CrefoPayRepositoryInterface getRepository()
 *
 * @SuppressWarnings(PHPMD.FactoryMethodReturnInterfaceRule)
 */
class CrefoPayPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayQuery
     */
    public function createPaymentCrefoPayQuery(): SpyPaymentCrefoPayQuery
    {
        return SpyPaymentCrefoPayQuery::create();
    }

    /**
     * @return \Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItemQuery
     */
    public function createPaymentCrefoPayOrderItemQuery(): SpyPaymentCrefoPayOrderItemQuery
    {
        return SpyPaymentCrefoPayOrderItemQuery::create();
    }

    /**
     * @return \Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItemToCrefoPayApiLogQuery
     */
    public function createPaymentCrefoPayOrderItemToCrefoPayApiLogQuery(): SpyPaymentCrefoPayOrderItemToCrefoPayApiLogQuery
    {
        return SpyPaymentCrefoPayOrderItemToCrefoPayApiLogQuery::create();
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
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function createSpySalesOrderItemQuery(): SpySalesOrderItemQuery
    {
        return SpySalesOrderItemQuery::create();
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Persistence\Mapper\CrefoPayPersistenceMapperInterface
     */
    public function createCrefoPayPersistenceMapper(): CrefoPayPersistenceMapperInterface
    {
        return new CrefoPayPersistenceMapper();
    }
}
