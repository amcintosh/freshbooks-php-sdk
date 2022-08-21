<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Casters\ArrayCaster;
use amcintosh\FreshBooks\Model\AccountingList;
use amcintosh\FreshBooks\Model\Payment;

/**
 * Results of Payments list call containing list of payments and pagination data.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/payments
 */
class PaymentList extends AccountingList
{
    public const RESPONSE_FIELD = 'payments';

    #[CastWith(ArrayCaster::class, itemType: Payment::class)]
    public array $payments;
}
