<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Attributes\MapTo;
use Spatie\DataTransferObject\Casters\ArrayCaster;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Results of bill payments list call containing list of bill payments and pagination data.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/bill_payments
 */
class BillPaymentList extends DataTransferObject
{
    public const RESPONSE_FIELD = 'bill_payments';

    #[MapFrom('bill_payments')]
    #[MapTo('bill_payments')]
    #[CastWith(ArrayCaster::class, itemType: BillPayment::class)]
    public array $billsPayments;
}
