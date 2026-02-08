<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use DateTime;
use DateTimeImmutable;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Attributes\MapTo;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\DataTransferObject;
use amcintosh\FreshBooks\Model\DataModelLegacy;
use amcintosh\FreshBooks\Model\Money;
use amcintosh\FreshBooks\Model\Caster\AccountingDateTimeImmutableCaster;
use amcintosh\FreshBooks\Model\Caster\DateCaster;
use amcintosh\FreshBooks\Model\Caster\MoneyCaster;

/**
 * Payments are a record of the payments made on invoices.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/payments
 */
class Payment extends DataTransferObject implements DataModelLegacy
{
    public const RESPONSE_FIELD = 'payment';

    /**
     * @var int The unique id (across this business) for the payment.
     */
    public ?int $id;

    /**
     * @var int Duplicate of id.
     *
     * _Note_: The API returns this as `logid`.
     */
    #[MapFrom('logid')]
    public ?int $paymentId;

    /**
     * @var string Unique identifier of account the payment exists on.
     */
    #[MapFrom('accounting_systemid')]
    public ?string $accountingSystemId;

    /**
     * @var Money The amount of the payment.
     *
     * Money object containing amount and currency code.
     */
    #[CastWith(MoneyCaster::class)]
    public ?Money $amount;

    #[MapFrom('bulk_paymentid')]
    #[MapTo('bulk_paymentid')]
    public ?int $bulkPaymentId;

    /**
     * @var int Id of client who made the payment.
     */
    #[MapFrom('clientid')]
    public ?int $clientId;

    /**
     * @var int The id of a related credit resource.
     */
    #[MapFrom('creditid')]
    #[MapTo('creditid')]
    public ?int $creditId;

    /**
     * @var DateTime Date the payment was made.
     *
     * The API returns this in YYYY-MM-DD format. It is converted to a DateTime.
     */
    #[MapFrom('date')]
    #[CastWith(DateCaster::class)]
    public ?DateTime $date;


    /**
     * @var bool If the payment was converted from a Credit on a Client's account.
     */
    #[MapFrom('from_credit')]
    #[MapTo('from_credit')]
    public ?bool $fromCredit;

    /**
     * @var string The payment processor (gateway) used to make the payment, if any.
     *
     * Eg. "stripe"
     */
    public ?string $gateway;

    /**
     * @var int The id of a related Invoice resource.
     */
    #[MapFrom('invoiceid')]
    #[MapTo('invoiceid')]
    public ?int $invoiceId;

    /**
     * @var string Notes on payment, often used for credit card reference number.
     *
     * **Do not store actual credit card numbers here.**
     */
    public ?string $note;

    #[MapFrom('orderid')]
    #[MapTo('orderid')]
    public ?int $orderId;

    /**
     * @var int Id of related overpayment Credit if relevant.
     */
    #[MapFrom('overpaymentid')]
    public ?int $overpaymentId;

    /**
     * @var bool Whether to send the client a notification of this payment.
     */
    #[MapFrom('send_client_notification')]
    #[MapTo('send_client_notification')]
    public ?bool $sendClientNotification;

    /**
     * @var string Type of payment made.
     *
     * Eg. “Check”, “Credit”, “Cash”
     */
    public ?string $type;

    /**
     * @var DateTimeImmutable The time of last modification.
     *
     * _Note:_ The API returns this data in "US/Eastern", but it is converted here to UTC.
     */
    #[CastWith(AccountingDateTimeImmutableCaster::class)]
    public ?DateTimeImmutable $updated;

    /**
     * @var int The visibility state: active, deleted, or archived
     *
     * See [FreshBooks API - Active and Deleted Objects](https://www.freshbooks.com/api/active_deleted)
     */
    #[MapFrom('vis_state')]
    public ?int $visState;

    /**
     * Get the data as an array to POST or PUT to FreshBooks, removing any read-only fields.
     *
     * @return array
     */
    public function getContent(): array
    {
        $data = $this
            ->except('id')
            ->except('accountingSystemId')
            ->except('paymentId')
            ->except('clientId')
            ->except('date')
            ->except('gateway')
            ->except('overpaymentId')
            ->except('updated')
            ->except('visState')
            ->toArray();
        if (isset($this->date)) {
            $data['date'] = $this->date->format('Y-m-d');
        }
        foreach ($data as $key => $value) {
            if (is_null($value)) {
                unset($data[$key]);
            }
        }
        return $data;
    }
}
