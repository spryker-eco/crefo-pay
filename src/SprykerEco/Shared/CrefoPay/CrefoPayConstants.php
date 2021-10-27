<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Shared\CrefoPay;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface CrefoPayConstants
{
    /**
     * Specification:
     *  - Merchant ID provided by CrefoPay, int value.
     *
     * @api
     *
     * @var string
     */
    public const MERCHANT_ID = 'CREFO_PAY:MERCHANT_ID';

    /**
     * Specification:
     *  - Store ID provided by CrefoPay, string value.
     *
     * @api
     *
     * @var string
     */
    public const STORE_ID = 'CREFO_PAY:STORE_ID';

    /**
     * Specification:
     *  - Refund description that will be shown in merchant backend.
     *  - Will be taken if comment is not present in RefundTransfer.
     *
     * @api
     *
     * @var string
     */
    public const REFUND_DESCRIPTION = 'CREFO_PAY:REFUND_DESCRIPTION';

    /**
     * Specification:
     *  - Url for secureFields API requests.
     *
     * @api
     *
     * @var string
     */
    public const SECURE_FIELDS_API_ENDPOINT = 'CREFO_PAY:SECURE_FIELDS_API_ENDPOINT';

    /**
     * Specification:
     *  - Placeholders that will be shown in Credit Card form and Direct Debit form, array value.
     *
     * @api
     *
     * @var string
     */
    public const SECURE_FIELDS_PLACEHOLDERS = 'CREFO_PAY:SECURE_FIELDS_PLACEHOLDERS';

    /**
     * Specification:
     *  - Represents integration model: true in case of b2b and false in case of b2c, bool value.
     *
     * @api
     *
     * @var string
     */
    public const IS_BUSINESS_TO_BUSINESS = 'CREFO_PAY:IS_BUSINESS_TO_BUSINESS';

    /**
     * Specification:
     *  - If true makes capture request for expenses as separate transaction.
     *  - If false makes capture for expenses with first captured order item.
     *
     * @api
     *
     * @var string
     */
    public const CAPTURE_EXPENSES_SEPARATELY = 'CREFO_PAY:CAPTURE_EXPENSES_SEPARATELY';

    /**
     * Specification:
     *  - If true makes REQUEST request for expenses when last order item is refunded.
     *  - If false does not perform any actions related to expenses refund.
     *
     * @api
     *
     * @var string
     */
    public const REFUND_EXPENSES_WITH_LAST_ITEM = 'CREFO_PAY:REFUND_EXPENSES_WITH_LAST_ITEM';
}
