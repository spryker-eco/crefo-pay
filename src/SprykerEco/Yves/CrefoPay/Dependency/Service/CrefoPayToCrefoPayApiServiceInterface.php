<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\CrefoPay\Dependency\Service;

interface CrefoPayToCrefoPayApiServiceInterface
{
    /**
     * @param array $data
     * @param string $mac
     *
     * @return bool
     */
    public function validateMac(array $data, string $mac): bool;
}
