<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\CrefoPay\Business;

use Generated\Shared\Transfer\CrefoPayNotificationTransfer;
use Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayNotification;

/**
 * @group Functional
 * @group SprykerEco
 * @group Zed
 * @group CrefoPay
 * @group Business
 */
class ProcessNotificationFacadeTest extends CrefoPayFacadeBaseTest
{
    /**
     * @return void
     */
    public function testProcessNotification(): void
    {
        $notificationTransfer = $this->tester->createCrefoPayNotificationTransfer();
        $notificationTransfer = $this->facade->processNotification($notificationTransfer);
        $this->doTest($notificationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayNotificationTransfer $notificationTransfer
     *
     * @return void
     */
    public function doTest(CrefoPayNotificationTransfer $notificationTransfer): void
    {
        /** @var \Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayNotification $notification */
        $notification = $this->tester->createPaymentCrefoPayNotificationQuery()
            ->filterByCrefoPayOrderId($notificationTransfer->getOrderID())
            ->findOne();

        $this->assertInstanceOf(SpyPaymentCrefoPayNotification::class, $notification);
        $this->assertEquals($notificationTransfer->getTransactionStatus(), $notification->getTransactionStatus());
        $this->assertEquals($notificationTransfer->getOrderStatus(), $notification->getOrderStatus());
    }
}
