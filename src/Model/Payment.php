<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use DateTime;
use DateTimeImmutable;
use amcintosh\FreshBooks\Model\DataModel;
use amcintosh\FreshBooks\Model\Money;
use amcintosh\FreshBooks\Util;

/**
 * Payments are a record of the payments made on invoices.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/payments
 */
class Payment implements DataModel
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
    public ?int $paymentId;

    /**
     * @var string Unique identifier of account the payment exists on.
     */
    public ?string $accountingSystemId;

    /**
     * @var Money The amount of the payment.
     *
     * Money object containing amount and currency code.
     */
    public ?Money $amount = null;

    public ?int $bulkPaymentId;

    /**
     * @var int Id of client who made the payment.
     */
    public ?int $clientId;

    /**
     * @var int The id of a related credit resource.
     */
    public ?int $creditId;

    /**
     * @var DateTime Date the payment was made.
     *
     * The API returns this in YYYY-MM-DD format. It is converted to a DateTime.
     */
    public ?DateTime $date;


    /**
     * @var bool If the payment was converted from a Credit on a Client's account.
     */
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
    public ?int $invoiceId;

    /**
     * @var string Notes on payment, often used for credit card reference number.
     *
     * **Do not store actual credit card numbers here.**
     */
    public ?string $note;

    public ?int $orderId;

    /**
     * @var int Id of related overpayment Credit if relevant.
     */
    public ?int $overpaymentId;

    /**
     * @var bool Whether to send the client a notification of this payment.
     */
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
    public ?DateTimeImmutable $updated;

    /**
     * @var int The visibility state: active, deleted, or archived
     *
     * See [FreshBooks API - Active and Deleted Objects](https://www.freshbooks.com/api/active_deleted)
     */
    public ?int $visState;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->paymentId = $data['logid'] ?? null;
        $this->accountingSystemId = $data['accounting_systemid'] ?? null;
        $this->visState = $data['vis_state'] ?? null;
        if (isset($data['amount'])) {
            $this->amount = new Money($data['amount']['amount'], $data['amount']['code']);
        }
        $this->bulkPaymentId = $data['bulk_paymentid'] ?? null;
        $this->clientId = $data['clientid'] ?? null;
        $this->creditId = $data['creditid'] ?? null;
        if (isset($data['date'])) {
            $this->date = Util::getDate($data['date']);
        }
        $this->fromCredit = $data['from_credit'] ?? null;
        $this->gateway = $data['gateway'] ?? null;
        $this->invoiceId = $data['invoiceid'] ?? null;
        $this->note = $data['note'] ?? null;
        $this->orderId = $data['orderid'] ?? null;
        $this->overpaymentId = $data['overpaymentid'] ?? null;
        $this->sendClientNotification = $data['send_client_notification'] ?? null;
        $this->type = $data['type'] ?? null;
        if (isset($data['updated'])) {
            $this->updated = Util::getAccountingDateTime($data['updated']);
        }
    }

    /**
     * Get the data as an array to POST or PUT to FreshBooks, removing any read-only fields.
     *
     * @return array
     */
    public function getContent(): array
    {
        $data = array();
        Util::convertContent($data, 'amount', $this->amount);
        Util::convertContent($data, 'bulk_paymentid', $this->bulkPaymentId);
        Util::convertContent($data, 'creditid', $this->creditId);
        Util::convertContent($data, 'from_credit', $this->fromCredit);
        Util::convertContent($data, 'invoiceid', $this->invoiceId);
        Util::convertContent($data, 'note', $this->note);
        Util::convertContent($data, 'orderid', $this->orderId);
        Util::convertContent($data, 'send_client_notification', $this->sendClientNotification);
        Util::convertContent($data, 'type', $this->type);
        if (isset($this->date)) {
            $data['date'] = $this->date->format('Y-m-d');
        }
        return $data;
    }
}
