<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use DateTime;
use DateTimeImmutable;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Attributes\MapTo;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\Casters\ArrayCaster;
use Spatie\DataTransferObject\Casters\DataTransferObjectCaster;
use Spatie\DataTransferObject\DataTransferObject;
use amcintosh\FreshBooks\Model\DataModel;
use amcintosh\FreshBooks\Model\InvoicePresentation;
use amcintosh\FreshBooks\Model\LineItem;
use amcintosh\FreshBooks\Model\Money;
use amcintosh\FreshBooks\Model\Caster\AccountingDateTimeImmutableCaster;
use amcintosh\FreshBooks\Model\Caster\DateCaster;
use amcintosh\FreshBooks\Model\Caster\MoneyCaster;

/**
 * Invoices in FreshBooks are what gets sent to Clients, detailing specific goods or
 * services performed or provided by the Administrator of their System, and the amount
 * that Client owes to the Admin.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/invoices
 */
class Invoice extends DataTransferObject implements DataModel
{
    public const RESPONSE_FIELD = 'invoice';

    /**
     * @var int The unique identifier of this invoice within this business.
     */
    public ?int $id;

    /**
     * @var int The unique identifier of this invoice within this business.
     */
    #[MapFrom('invoiceid')]
    #[MapTo('invoiceid')]
    public ?int $invoiceId;

    /**
     * @var string Unique identifier of account the invoice exists on.
     */
    #[MapFrom('accounting_systemid')]
    public ?string $accountingSystemId;

    /**
     * @var string Get unique identifier of the account the invoice exists on.
     *
     * The same as <code>getAccountingSystemId()</code>.
     */
    #[MapFrom('accountid')]
    public ?string $accountId;

    /**
     * @var string First line of address on invoice.
     */
    public ?string $address;

    /**
     * @var Money Total amount of invoice.
     *
     * Money object containing amount and currency code.
     */
    #[CastWith(MoneyCaster::class)]
    public ?Money $amount;

    /**
     * @var bool Whether this invoice has a credit card saved.
     */
    #[MapFrom('auto_bill')]
    #[MapTo('auto_bill')]
    public ?bool $autoBill;

    /**
     * @var string Whether this invoice has a credit card saved.
     *
     * Values are: `failed`, `retry`, `success`.
     */
    #[MapFrom('autobill_status')]
    public ?string $autoBillStatus;

    /**
     * @var string City for address on invoice.
     *
     */
    public ?string $city;

    /**
     * @var string Postal/ZIP code for address on invoice.
     *
     */
    public ?string $code;

    /**
     * @var string Country for address on invoice.
     *
     */
    public ?string $country;

    /**
     * @var int The id of the client the invoice is for.
     *
     * _Note:_ The API request/response uses `customerid` rather than `clientid`.
     */
    #[MapFrom('customerid')]
    #[MapTo('customerid')]
    public ?int $clientId;

    /**
     * @var DateTime Date the invoice was created.
     *
     * The API returns this in YYYY-MM-DD format. It is converted to a DateTime.
     */
    #[MapFrom('create_date')]
    #[MapTo('create_date')]
    #[CastWith(DateCaster::class)]
    public ?DateTime $createDate;

    /**
     * @var DateTimeImmutable The date/time the invoice was created.
     *
     * _Note:_ The API returns this data in "US/Eastern", but it is converted here to UTC.
     */
    #[MapFrom('created_at')]
    #[CastWith(AccountingDateTimeImmutableCaster::class)]
    public ?DateTimeImmutable $createdAt;

    /**
     * @var string Three-letter currency code for invoice.
     */
    #[MapFrom('currency_code')]
    #[MapTo('currency_code')]
    public ?string $currencyCode;

    /**
     * @var string The current name of the client's organization.
     *
     * Once an invoice is set, the organization will not reflect any changes to the client
     * but the current organization is available.
     */
    #[MapFrom('current_organization')]
    public ?string $currentOrganization;

    /**
     * @var DateTime Date invoice was fully paid.
     *
     * The API returns this in YYYY-MM-DD format.
     */
    #[MapFrom('date_paid')]
    #[CastWith(DateCaster::class)]
    public ?DateTime $datePaid;

    /**
     * @var Money Amount required as deposit if required.
     *
     * Money object containing amount and currency code.
     */
    #[MapFrom('deposit_amount')]
    #[MapTo('deposit_amount')]
    #[CastWith(MoneyCaster::class)]
    public ?Money $depositAmount;

    /**
     * @var string Percent of the invoice's value required as a deposit.
     */
    #[MapFrom('deposit_percentage')]
    #[MapTo('deposit_percentage')]
    public ?string $depositPercentage;

    /**
     * @var string Description of deposits applied to invoice.
     *
     * _Note:_ This is only writeable on creation.
     *
     * Values: `paid`, `unpaid`, `partial`, `converted`, `none`.
     */
    #[MapFrom('deposit_status')]
    public ?string $depositStatus;

    /**
     * @var string Description of first line of invoice
     */
    public ?string $description;

    /**
     * @var string Client-visible note about discount.
     */
    #[MapFrom('discount_description')]
    #[MapTo('discount_description')]
    public ?string $discountDescription;

    /**
     * @var Money Amount outstanding on invoice.
     *
     * Money object containing amount and currency code.
     */
    #[MapFrom('discount_total')]
    #[MapTo('discount_total')]
    #[CastWith(MoneyCaster::class)]
    public ?Money $discountTotal;

    /**
     * @var float Percent amount being discounted from the subtotal.
     */
    #[MapFrom('discount_value')]
    #[MapTo('discount_value')]
    public ?float $discountValue;

    /**
     * @var string Description of status. Used primarily for the FreshBooks UI.
     */
    #[MapFrom('display_status')]
    #[MapTo('display_status')]
    public ?string $displayStatus;

    /**
     * @var DateTime Date invoice is marked as due by calculated from `due_offset_days`.
     *
     * If `due_offset_days` is not set, it will default to the date of issue.
     *
     * The API returns this in YYYY-MM-DD format.
     */
    #[MapFrom('due_date')]
    #[CastWith(DateCaster::class)]
    public ?DateTime $dueDate;

    /**
     * @var int Number of days from creation that invoice is due.
     */
    #[MapFrom('due_offset_days')]
    #[MapTo('due_offset_days')]
    public ?int $dueOffsetDays;

    /**
     * @var int Id of associated estimate, 0 if none.
     */
    #[MapFrom('estimateid')]
    #[MapTo('estimateid')]
    public ?int $estimateId;

    /**
     * @var string First name of client being invoiced.
     */
    #[MapFrom('fname')]
    #[MapTo('fname')]
    public ?string $firstName;

    /**
     * @var DateTime The date the invoice was generated from a invoice profile or null if it was not generated.
     *
     * The API returns this in YYYY-MM-DD format.
     */
    #[MapFrom('generation_date')]
    #[CastWith(DateCaster::class)]
    public ?DateTime $generationDate;

    /**
     * @var bool Whether invoice should be sent via ground mail.
     */
    #[MapFrom('gmail')]
    public ?bool $groundMail;

    /**
     * @var string The user-specified number that appears on the invoice.
     */
    #[MapFrom('invoice_number')]
    #[MapTo('invoice_number')]
    public ?string $invoiceNumber;

    /**
     * @var string Describes status of last attempted payment.
     */
    public ?string $language;

    /**
     * @var string Describes status of last attempted payment.
     *
     * _Note:_ This is only writeable on creation.
     */
    #[MapFrom('last_order_status')]
    #[MapTo('last_order_status')]
    public ?string $lastOrderStatus;

    /**
     * @var string Last name of client being invoiced.
     */
    #[MapFrom('lname')]
    #[MapTo('lname')]
    public ?string $lastName;

    /**
     * @var array Lines of the invoice.
     *
     * _Note:_ These are only returned with a invoice call using a "lines" include.
     * TODO: code example
     */
    #[CastWith(ArrayCaster::class, itemType: LineItem::class)]
    public ?array $lines;

    /**
     * @var string Notes listed on invoice.
     */
    public ?string $notes;

    /**
     * @var string Name of organization being invoiced.
     *
     * This is the value of the organization of the client but is denormalized so that
     * changes to the client are not reflected on past invoices.
     */
    public ?string $organization;

    /**
     * @var Money Amount outstanding on invoice.
     *
     * Money object containing amount and currency code.
     */
    #[CastWith(MoneyCaster::class)]
    public ?Money $outstanding;

    /**
     * @var int Id of creator of invoice. 1 if business admin, other if created by another user. Eg. a contractor.
     */
    #[MapFrom('ownerid')]
    #[MapTo('ownerid')]
    public ?int $ownerId;

    /**
     * @var Money Amount paid on invoice.
     *
     * Money object containing amount and currency code.
     */
    #[CastWith(MoneyCaster::class)]
    public ?Money $paid;

    /**
     * @var int Id of object this invoice was generated from, 0 if none
     */
    public ?int $parent;

    /**
     * @var string Description of payment status.
     *
     * One of `unpaid`, `partial`, `paid`, and `auto-paid`.
     */
    #[MapFrom('payment_status')]
    #[MapTo('payment_status')]
    public ?string $paymentStatus;

    /**
     * @var string Reference number for address on invoice.
     */
    #[MapFrom('po_number')]
    #[MapTo('po_number')]
    public ?string $PONumber;

    /**
     * @var InvoicePresentation Define invoice logo and styles.
     *
     * By default, if no presentation specified in a new invoice request payload,
     * it will be assigned a default presentation. To override this default behaviour,
     * set `useDefaultPresentation` to false.
     *
     * _Note:_ The presentation details are only returned with a invoice call
     * using a "presentation" include.
     */
    public ?InvoicePresentation $presentation;

    /**
     * @var string Province/state for address on invoice.
     */
    public ?string $province;

    /**
     * @var int User id of user who sent the invoice. Typically 1 for the business admin.
     */
    #[MapFrom('sentid')]
    #[MapTo('sentid')]
    public ?int $sentId;

    /**
     * @var bool Whether attachments on invoice are rendered in FreshBooks UI.
     */
    #[MapFrom('show_attachments')]
    #[MapTo('show_attachments')]
    public ?bool $showAttachments;

    /**
     * @var string Street for address on invoice.
     */
    public ?string $street;

    /**
     * @var string Second line of street for address on invoice.
     */
    public ?string $street2;

    /**
     * @var int Set the status of the invoice.
     *
     * This is only writable at creation and cannot be manually set later.
     *
     * Values are:
     *
     * - **0:** DISPUTED. An Invoice with the Dispute option enabled, which has been disputed by a Client.
     *   This is a feature of FreshBooks Classic only and should only appear in migrated accounts.
     * - **1:** DRAFT. An Invoice that has been created, but not yet sent.
     * - **2**: SENT. An Invoice that has been sent to a Client or marked as sent.
     * - **3**: VIEWED. An Invoice that has been viewed by a Client.
     * - **4**: PAID. A fully paid Invoice.
     * - **5**: AUTOPAID. An Invoice paid automatically with a saved credit card.
     * - **6**: RETRY. An Invoice that would normally be paid automatically, but encountered a processing
     *   issue, either due to a bad card or a service outage. It will be retried 1 day later.
     * - **7**: FAILED. An Invoice that was in Retry status which again encountered a processing
     *   issue when being retried.
     * - **8**: PARTIAL. An Invoice that has been partially paid.
     *
     * @see InvoiceStatus for a value constants.
     * @link https://www.freshbooks.com/api/invoices
     */
    public ?int $status;

    /**
     * @var string Terms listed on invoice.
     */
    public ?string $terms;

    /**
     * @var DateTimeImmutable The time of last modification.
     */
    #[CastWith(AccountingDateTimeImmutableCaster::class)]
    public ?DateTimeImmutable $updated;

    /**
     * @var bool Whether invoice should use the default presentation.
     *
     * By default, if no presentation specified in new invoice request payload
     * it will be assigned a default presentation.
     *
     * To override this default behaviour, set useDefaultPresentation to false.
     */
    #[MapFrom('use_default_presentation')]
    #[MapTo('use_default_presentation')]
    public ?bool $useDefaultPresentation;


    /**
     * @var string v3 status fields give a descriptive name to states which can be used in filters.
     *
     * _Note:_ This is only writeable on creation.
     *
     * Values are:
     *
     * - **created**: Invoice is created and in no other state
     * - **draft**: Invoice is saved in draft status
     * - **sent**: Invoice has been sent
     * - **viewed**: Invoice has been viewed by recipient
     * - **failed**: An autobill related to the invoice has been tried more than once and failed
     * - **retry**: An autobill related to the invoice has been tried once and failed, and will be retried
     * - **success**: An autobill related to the invoice has succeeded
     * - **autopaid**: A payment has been tied to the invoice automatically via autobill
     * - **paid**: Payments related to the invoice have succeeded and the object is fully paid
     * - **partial**: Some payment related to the invoice has succeeded but the invoice is not yet paid off
     * - **disputed**: The invoice is disputed
     * - **resolved**: The invoice was disputed and the dispute has been marked as resolved
     * - **overdue**: The invoice required an action at an earlier date that was not met
     * - **deposit_partial**: The invoice has a related deposit which has been partially paid
     * - **deposit_paid**: The invoice has a related deposit which has been fully paid
     * - **declined**: The invoice has a related order which has been declined
     * - **pending**: The invoice has a related order which is pending
     *
     * @link https://www.freshbooks.com/api/invoices
     */
    #[MapFrom('v3_status')]
    #[MapTo('v3_status')]
    public ?string $v3Status;

    /**
     * @var string Value Added Tax name of client if provided.
     */
    #[MapFrom('vat_name')]
    #[MapTo('vat_name')]
    public ?string $VATName;

    /**
     * @var string Value Added Tax number of client.
     */
    #[MapFrom('vat_number')]
    #[MapTo('vat_number')]
    public ?string $VATNumber;

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
            ->except('accountId')
            ->except('autoBill')
            ->except('autoBillStatus')
            ->except('amount')
            ->except('createdAt')
            ->except('currentOrganization')
            ->except('datePaid')
            ->except('depositStatus')
            ->except('description')
            ->except('display_status')
            ->except('dueDate')
            ->except('estimateid')
            ->except('generationDate')
            ->except('groundMail')
            ->except('invoiceId')
            ->except('lastOrderStatus')
            ->except('organization')
            ->except('outstanding')
            ->except('ownerid')
            ->except('paid')
            ->except('payment_status')
            ->except('sentid')
            ->except('status')
            ->except('updated')
            ->except('v3_status')
            ->except('visState')
            ->toArray();
        if (is_null($this->id) && is_null($this->invoiceId)) {
            $data['ownerid'] = $this->ownerId;
            $data['estimateid'] = $this->estimateId;
            $data['sentid'] = $this->sentId;
            $data['status'] = $this->status;
            $data['display_status'] = $this->displayStatus;
            $data['autobill_status'] = $this->autoBillStatus;
            $data['payment_status'] = $this->paymentStatus;
            $data['last_order_status'] = $this->lastOrderStatus;
            $data['deposit_status'] = $this->depositStatus;
            $data['auto_bill'] = $this->autoBill;
            $data['v3_status'] = $this->v3Status;
        } else {
            unset($data['discount_total']);
        }
        if (isset($this->createDate)) {
            $data['create_date'] = $this->createDate->format('Y-m-d');
        }
        if (isset($this->generationDate)) {
            $data['generation_date'] = $this->generationDate->format('Y-m-d');
        }
        foreach ($data as $key => $value) {
            if (is_null($value)) {
                unset($data[$key]);
            }
        }
        return $data;
    }
}
