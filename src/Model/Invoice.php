<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use DateTime;
use DateTimeImmutable;
use amcintosh\FreshBooks\Model\DataModel;
use amcintosh\FreshBooks\Model\InvoicePresentation;
use amcintosh\FreshBooks\Model\LineItem;
use amcintosh\FreshBooks\Model\Money;
use amcintosh\FreshBooks\Util;

/**
 * Invoices in FreshBooks are what gets sent to Clients, detailing specific goods or
 * services performed or provided by the Administrator of their System, and the amount
 * that Client owes to the Admin.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/invoices
 */
class Invoice implements DataModel
{
    public const RESPONSE_FIELD = 'invoice';

    /**
     * @var int The unique identifier of this invoice within this business.
     */
    public ?int $id;

    /**
     * @var int The unique identifier of this invoice within this business.
     */
    public ?int $invoiceId;

    /**
     * @var string Unique identifier of account the invoice exists on.
     */
    public ?string $accountingSystemId;

    /**
     * @var string Get unique identifier of the account the invoice exists on.
     *
     * The same as <code>getAccountingSystemId()</code>.
     */
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
    public ?Money $amount;

    /**
     * @var bool Whether this invoice has a credit card saved.
     */
    public ?bool $autoBill;

    /**
     * @var string Whether this invoice has a credit card saved.
     *
     * Values are: `failed`, `retry`, `success`.
     */
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
    public ?int $clientId;

    /**
     * @var DateTime Date the invoice was created.
     *
     * The API returns this in YYYY-MM-DD format. It is converted to a DateTime.
     */
    public ?DateTime $createDate;

    /**
     * @var DateTimeImmutable The date/time the invoice was created.
     *
     * _Note:_ The API returns this data in "US/Eastern", but it is converted here to UTC.
     */
    public ?DateTimeImmutable $createdAt;

    /**
     * @var string Three-letter currency code for invoice.
     */
    public ?string $currencyCode;

    /**
     * @var string The current name of the client's organization.
     *
     * Once an invoice is set, the organization will not reflect any changes to the client
     * but the current organization is available.
     */
    public ?string $currentOrganization;

    /**
     * @var DateTime Date invoice was fully paid.
     *
     * The API returns this in YYYY-MM-DD format.
     */
    public ?DateTime $datePaid;

    /**
     * @var Money Amount required as deposit if required.
     *
     * Money object containing amount and currency code.
     */
    public ?Money $depositAmount;

    /**
     * @var string Percent of the invoice's value required as a deposit.
     */
    public ?string $depositPercentage;

    /**
     * @var string Description of deposits applied to invoice.
     *
     * _Note:_ This is only writeable on creation.
     *
     * Values: `paid`, `unpaid`, `partial`, `converted`, `none`.
     */
    public ?string $depositStatus;

    /**
     * @var string Description of first line of invoice
     */
    public ?string $description;

    /**
     * @var string Client-visible note about discount.
     */
    public ?string $discountDescription;

    /**
     * @var Money Amount discounted.
     *
     * Money object containing amount and currency code.
     */
    public ?Money $discountTotal;

    /**
     * @var float Percent amount being discounted from the subtotal.
     */
    public ?float $discountValue;

    /**
     * @var string Description of status. Used primarily for the FreshBooks UI.
     */
    public ?string $displayStatus;

    /**
     * @var DateTime Date invoice is marked as due by calculated from `due_offset_days`.
     *
     * If `due_offset_days` is not set, it will default to the date of issue.
     *
     * The API returns this in YYYY-MM-DD format.
     */
    public ?DateTime $dueDate;

    /**
     * @var int Number of days from creation that invoice is due.
     */
    public ?int $dueOffsetDays;

    /**
     * @var int Id of associated estimate, 0 if none.
     */
    public ?int $estimateId;

    /**
     * @var string First name of client being invoiced.
     */
    public ?string $firstName;

    /**
     * @var DateTime The date the invoice was generated from a invoice profile or null if it was not generated.
     *
     * The API returns this in YYYY-MM-DD format.
     */
    public ?DateTime $generationDate;

    /**
     * @var bool Whether invoice should be sent via ground mail.
     */
    public ?bool $groundMail;

    /**
     * @var string The user-specified number that appears on the invoice.
     */
    public ?string $invoiceNumber;

    /**
     * @var string Two-letter language code (e.g. "en", "fr")
     */
    public ?string $language;

    /**
     * @var string Describes status of last attempted payment.
     *
     * _Note:_ This is only writeable on creation.
     */
    public ?string $lastOrderStatus;

    /**
     * @var string Last name of client being invoiced.
     */
    public ?string $lastName;

    /**
     * @var array Lines of the invoice.
     *
     * _Note:_ These are only returned with a invoice call using a "lines" include.
     * TODO: code example
     */
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
    public ?Money $outstanding;

    /**
     * @var int Id of creator of invoice. 1 if business admin, other if created by another user. Eg. a contractor.
     */
    public ?int $ownerId;

    /**
     * @var Money Amount paid on invoice.
     *
     * Money object containing amount and currency code.
     */
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
    public ?string $paymentStatus;

    /**
     * @var string Reference number for address on invoice.
     */
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
    public ?int $sentId;

    /**
     * @var bool Whether attachments on invoice are rendered in FreshBooks UI.
     */
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
    public ?DateTimeImmutable $updated;

    /**
     * @var bool Whether invoice should use the default presentation.
     *
     * By default, if no presentation specified in new invoice request payload
     * it will be assigned a default presentation.
     *
     * To override this default behaviour, set useDefaultPresentation to false.
     */
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
    public ?string $v3Status;

    /**
     * @var string Value Added Tax name of client if provided.
     */
    public ?string $VATName;

    /**
     * @var string Value Added Tax number of client.
     */
    public ?string $VATNumber;

    /**
     * @var int The visibility state: active, deleted, or archived
     *
     * See [FreshBooks API - Active and Deleted Objects](https://www.freshbooks.com/api/active_deleted)
     */
    public ?int $visState;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->invoiceId = $data['invoiceid'] ?? null;
        $this->accountingSystemId = $data['accounting_systemid'] ?? null;
        $this->accountId = $data['accountid'] ?? null;
        $this->address = $data['address'] ?? null;
        if (isset($data['amount'])) {
            $this->amount = new Money($data['amount']['amount'], $data['amount']['code']);
        }
        $this->autoBill = $data['auto_bill'] ?? null;
        $this->autoBillStatus = $data['autobill_status'] ?? null;
        $this->city = $data['city'] ?? null;
        $this->code = $data['code'] ?? null;
        $this->country = $data['country'] ?? null;
        $this->clientId = $data['customerid'] ?? null;
        if (isset($data['create_date'])) {
            $this->createDate = Util::getDate($data['create_date']);
        }
        if (isset($data['created_at'])) {
            $this->createdAt = Util::getAccountingDateTime($data['created_at']);
        }
        $this->currencyCode = $data['currency_code'] ?? null;
        $this->currentOrganization = $data['current_organization'] ?? null;
        if (isset($data['date_paid'])) {
            $this->datePaid = Util::getDate($data['date_paid']);
        }
        if (isset($data['deposit_amount'])) {
            $this->depositAmount = new Money($data['deposit_amount']['amount'], $data['deposit_amount']['code']);
        }
        $this->depositPercentage = $data['deposit_percentage'] ?? null;
        $this->depositStatus = $data['deposit_status'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->discountDescription = $data['discount_description'] ?? null;
        if (isset($data['discount_total'])) {
            $this->discountTotal = new Money($data['discount_total']['amount'], $data['discount_total']['code']);
        }
        $this->discountValue = $data['discount_value'] ?? null;
        $this->displayStatus = $data['display_status'] ?? null;
        if (isset($data['due_date'])) {
            $this->dueDate = Util::getDate($data['due_date']);
        }
        $this->dueOffsetDays = $data['due_offset_days'] ?? null;
        $this->estimateId = $data['estimateid'] ?? null;
        $this->firstName = $data['fname'] ?? null;
        if (isset($data['generation_date'])) {
            $this->generationDate = Util::getDate($data['generation_date']);
        }
        $this->groundMail = $data['gmail'] ?? null;
        $this->invoiceNumber = $data['invoice_number'] ?? null;
        $this->language = $data['language'] ?? null;
        $this->lastOrderStatus = $data['last_order_status'] ?? null;
        $this->lastName = $data['lname'] ?? null;
        $this->notes = $data['notes'] ?? null;
        $this->organization = $data['organization'] ?? null;
        if (isset($data['outstanding'])) {
            $this->outstanding = new Money($data['outstanding']['amount'], $data['outstanding']['code']);
        }
        $this->ownerId = $data['ownerid'] ?? null;
        if (isset($data['paid'])) {
            $this->paid = new Money($data['paid']['amount'], $data['paid']['code']);
        }
        $this->parent = $data['parent'] ?? null;
        $this->paymentStatus = $data['payment_status'] ?? null;
        $this->PONumber = $data['po_number'] ?? null;
        if (isset($data['presentation'])) {
            $this->presentation = new InvoicePresentation($data['presentation']);
        }
        $this->province = $data['province'] ?? null;
        $this->sentId = $data['sentid'] ?? null;
        $this->showAttachments = $data['show_attachments'] ?? null;
        $this->street = $data['street'] ?? null;
        $this->street2 = $data['street2'] ?? null;
        $this->status = $data['status'] ?? null;
        $this->terms = $data['terms'] ?? null;
        if (isset($data['updated'])) {
            $this->updated = Util::getAccountingDateTime($data['updated']);
        }
        $this->useDefaultPresentation = $data['use_default_presentation'] ?? null;
        $this->v3Status = $data['v3_status'] ?? null;
        $this->VATName = $data['vat_name'] ?? null;
        $this->VATNumber = $data['vat_number'] ?? null;
        $this->visState = $data['vis_state'] ?? null;

        foreach ($data['lines'] ?? [] as $lineData) {
            $this->lines[] = new LineItem($lineData);
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
        Util::convertContent($data, 'address', $this->address);
        Util::convertContent($data, 'city', $this->city);
        Util::convertContent($data, 'code', $this->code);
        Util::convertContent($data, 'country', $this->country);
        if (isset($this->createDate)) {
            $data['create_date'] = $this->createDate->format('Y-m-d');
        }
        Util::convertContent($data, 'currency_code', $this->currencyCode);
        Util::convertContent($data, 'customerid', $this->clientId);
        Util::convertContent($data, 'deposit_amount', $this->depositAmount);
        Util::convertContent($data, 'deposit_percentage', $this->depositPercentage);
        Util::convertContent($data, 'discount_description', $this->discountDescription);
        Util::convertContent($data, 'discount_total', $this->discountTotal);
        Util::convertContent($data, 'discount_value', $this->discountValue);
        Util::convertContent($data, 'due_offset_days', $this->dueOffsetDays);
        Util::convertContent($data, 'fname', $this->firstName);
        Util::convertContent($data, 'invoice_number', $this->invoiceNumber);
        Util::convertContent($data, 'language', $this->language);
        Util::convertContent($data, 'lines', $this->lines);
        Util::convertContent($data, 'lname', $this->lastName);
        Util::convertContent($data, 'notes', $this->notes);
        Util::convertContent($data, 'parent', $this->parent);
        Util::convertContent($data, 'po_number', $this->PONumber);
        Util::convertContent($data, 'presentation', $this->presentation);
        Util::convertContent($data, 'province', $this->province);
        Util::convertContent($data, 'show_attachments', $this->showAttachments);
        Util::convertContent($data, 'street', $this->street);
        Util::convertContent($data, 'street2', $this->street2);
        Util::convertContent($data, 'terms', $this->terms);
        Util::convertContent($data, 'use_default_presentation', $this->useDefaultPresentation);
        Util::convertContent($data, 'vat_name', $this->VATName);
        Util::convertContent($data, 'vat_number', $this->VATNumber);

        if (is_null($this->id) && is_null($this->invoiceId)) {
            Util::convertContent($data, 'ownerid', $this->ownerId);
            Util::convertContent($data, 'estimateid', $this->estimateId);
            Util::convertContent($data, 'sentid', $this->sentId);
            Util::convertContent($data, 'status', $this->status);
            Util::convertContent($data, 'display_status', $this->displayStatus);
            Util::convertContent($data, 'autobill_status', $this->autoBillStatus);
            Util::convertContent($data, 'payment_status', $this->paymentStatus);
            Util::convertContent($data, 'last_order_status', $this->lastOrderStatus);
            Util::convertContent($data, 'deposit_status', $this->depositStatus);
            Util::convertContent($data, 'auto_bill', $this->autoBill);
            Util::convertContent($data, 'v3_status', $this->v3Status);
        } else {
            unset($data['discount_total']);
        }
        if (isset($this->generationDate)) {
            $data['generation_date'] = $this->generationDate->format('Y-m-d');
        }
        return $data;
    }
}
