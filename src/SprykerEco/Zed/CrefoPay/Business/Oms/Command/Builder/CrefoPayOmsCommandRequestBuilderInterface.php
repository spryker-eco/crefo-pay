<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Oms\Command\Builder;

use Generated\Shared\Transfer\CrefoPayOmsCommandTransfer;

interface CrefoPayOmsCommandRequestBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer
     */
    public function buildRequestTransfer(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): CrefoPayOmsCommandTransfer;
}
