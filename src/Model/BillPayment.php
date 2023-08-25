<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use amcintosh\FreshBooks\Model\Caster\DateCaster;
use amcintosh\FreshBooks\Model\Caster\MoneyCaster;
use DateTime;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Attributes\MapTo;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Bill Payments are a record of the payments made on a Bill.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/bill_payments
 */
class BillPayment extends DataTransferObject
{
    public const RESPONSE_FIELD = 'bill_payment';

    /**
     * @var int Uniquely identifies the payment associated with the business.
     */
    public ?int $id;

    /**
     * @var Money Payment amount.
     */
    #[CastWith(MoneyCaster::class)]
    public ?Money $amount;

    /**
     * @var int Id of the bill to create the payment for.
     */
    #[MapFrom('billid')]
    #[MapTo('billid')]
    public ?int $billId;

    /**
     * @var bool Internal use. If the Bill has been reconciled with a bank-imported expense.
     */
    #[MapFrom('matched_with_expense')]
    #[MapTo('matched_with_expense')]
    public ?bool $matchedWithExpense;

    /**
     * @var DateTime Date the payment was made, YYYY-MM-DD format.
     */
    #[MapFrom('paid_date')]
    #[MapTo('paid_date')]
    #[CastWith(DateCaster::class)]
    public ?DateTime $paidDate;

    /**
     * @var string "Check", "Credit", "Cash", etc.
     */
    #[MapFrom('payment_type')]
    #[MapTo('payment_type')]
    public ?string $paymentType;

    /**
     * @var string Notes on payment.
     */
    public ?string $note;

    /**
     * @var int 0 for active, 1 for deleted, 2 for archived.
     */
    #[MapFrom('vis_state')]
    #[MapTo('vis_state')]
    public ?int $visState;
}
