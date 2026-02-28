<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use DateTime;
use DateTimeImmutable;
use amcintosh\FreshBooks\Model\DataModel;
use amcintosh\FreshBooks\Model\ExpenseAttachment;
use amcintosh\FreshBooks\Util;

/**
 * Expenses are used to track expenditures your business incurs.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/expenses
 */
class Expense implements DataModel
{
    public const RESPONSE_FIELD = 'expense';

    /**
     * @var int The unique identifier of this expense within this business.
     */
    public ?int $id;

    /**
     * @var int Duplicate of id
     */
    public ?int $expenseId;

    /**
     * @var string Name of related account if applicable.
     */
    public ?string $accountName;

    /**
     * @var int Id of expense account if applicable.
     */
    public ?int $accountId;

    /**
     * @var string Unique identifier of business the expense exists on.
     */
    public ?string $accountingSystemId;

    /**
     * @var Money Total amount of invoice
     *
     * Money object containing amount and currency code.
     */
    public ?Money $amount = null;

    /**
     * @var string Name of bank expense was imported from, if applicable.
     */
    public ?string $bankName;

    /**
     * @var bool Can the expense be billed to a Client or Project.
     */
    public ?bool $billable;

    /**
     * @var int Id of the expense category.
     */
    public ?int $categoryId;

    /**
     * @var int Id of client the expense has been assigned to if applicable.
     */
    public ?int $clientId;

    /**
     * @var DateTime Date of the expense.
     *
     * The API returns this in YYYY-MM-DD format. It is converted to a DateTime.
     */
    public ?DateTime $date = null;

    /**
     * @var int The id of related contractor account if applicable.
     */
    public ?int $extAccountId;

    /**
     * @var int The id of related contractor invoice if applicable.
     */
    public ?int $extInvoiceId;

    /**
     * @var int The id of related contractor system if applicable.
     */
    public ?int $extSystemId;

    /**
     * @var bool Indicates if the expense was created via a bulk import action.
     */
    public ?bool $fromBulkImport;

    /**
     * @var bool Indicates if the expense has an attached receipt.
     */
    public ?bool $hasReceipt;

    public ?bool $includeReceipt;

    /**
     * @var int The id of related invoice if applicable.
     */
    public ?int $invoiceId;

    /**
     * @var bool If the expense counts as "Cost of Goods Sold" and is associated with a client.
     */
    public ?bool $isCogs;

    /**
     * @var bool If the expense is a duplicated expense.
     */
    public ?bool $isDuplicate;

    /**
     * @var string Note of percent to mark expense up.
     */
    public ?string $markupPercent;

    /**
     * @var int Id of related new FreshBooks (not Classic) project if applicable.
     */
    public ?int $modernProjectId;

    /**
     * @var string Notes about the expense.
     */
    public ?string $notes;

    public ?bool $potentialBillPayment;

    /**
     * @var int Id of related recurring expense profile if applicable.
     */
    public ?int $profileId;

    /**
     * @var int Id of related FreshBooks Classic project if applicable.
     */
    public ?int $projectId;

    /**
     * @var int The id of related staff member if applicable.
     */
    public ?int $staffId;

    /**
     * @var int The status of the expense.
     *
     * _Note:_ This is only writeable on creation.
     *
     * Values are:
     *
     * - **0:** INTERNAL. Internal rather than client
     *   This is a feature of FreshBooks Classic only and should only appear in migrated accounts.
     * - **1:** OUTSTANDING. Has client, needs to be applied to an invoice
     * - **2**: INVOICED. Has client, attached to an invoice
     * - **4**: RECOUPED. Has client, attached to an invoice, and paid
     *
     * @see ExpenseStatus for a value constants.
     * @link https://www.freshbooks.com/api/expenses
     */
    public ?int $status;

    /**
     * @var Money The amount of the first tax making up the total amount of the expense.
     *
     * Money object containing amount and currency code.
     */
    public ?Money $taxAmount1 = null;

    /**
     * @var Money The amount of the second tax making up the total amount of the expense.
     *
     * Money object containing amount and currency code.
     */
    public ?Money $taxAmount2 = null;

    /**
     * @var string The name of first tax.
     */
    public ?string $taxName1;

    /**
     * @var string The name of second tax.
     */
    public ?string $taxName2;

    /**
     * @var string The string-decimal tax amount for first tax – indicates the maximum tax percentage for this expense.
     */
    public ?string $taxPercent1;

    /**
     * @var string The string-decimal tax amount for second tax – indicates the maximum tax percentage for this expense.
     */
    public ?string $taxPercent2;

    /**
     * @var int The id of related bank imported transaction if applicable.
     */
    public ?int $transactionId;

    /**
     * @var DateTimeImmutable The time of last modification.
     */
    public ?DateTimeImmutable $updated;

    /**
     * @var string The name of the vendor.
     */
    public ?string $vendor;

    /**
     * @var int The visibility state: active, deleted, or archived
     *
     * See [FreshBooks API - Active and Deleted Objects](https://www.freshbooks.com/api/active_deleted)
     */
    public ?int $visState;

    // Includes

    /**
     * @var ExpenseAttachment Attached receipt.
     */
    public ?ExpenseAttachment $attachment = null;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->expenseId = $data['expenseid'] ?? null;
        $this->accountName = $data['account_name'] ?? null;
        $this->accountId = $data['accountid'] ?? null;
        $this->accountingSystemId = $data['accounting_systemid'] ?? null;
        if (isset($data['amount'])) {
            $this->amount = new Money($data['amount']['amount'], $data['amount']['code']);
        }
        $this->bankName = $data['bank_name'] ?? null;
        $this->billable = $data['billable'] ?? null;
        $this->categoryId = $data['categoryid'] ?? null;
        $this->clientId = $data['clientid'] ?? null;
        if (isset($data['date'])) {
            $this->date = Util::getDate($data['date']);
        }
        $this->extAccountId = $data['ext_accountid'] ?? null;
        $this->extInvoiceId = $data['ext_invoiceid'] ?? null;
        $this->extSystemId = $data['ext_systemid'] ?? null;
        $this->fromBulkImport = $data['from_bulk_import'] ?? null;
        $this->hasReceipt = $data['has_receipt'] ?? null;
        $this->includeReceipt = $data['include_receipt'] ?? null;
        $this->invoiceId = $data['invoiceid'] ?? null;
        $this->isCogs = $data['is_cogs'] ?? null;
        $this->isDuplicate = $data['isduplicate'] ?? null;
        $this->markupPercent = $data['markup_percent'] ?? null;
        $this->modernProjectId = $data['modern_projectid'] ?? null;
        $this->notes = $data['notes'] ?? null;
        $this->potentialBillPayment = $data['potential_bill_payment'] ?? null;
        $this->profileId = $data['profileid'] ?? null;
        $this->projectId = $data['projectid'] ?? null;
        $this->staffId = $data['staffid'] ?? null;
        $this->status = $data['status'] ?? null;
        if (isset($data['taxAmount1'])) {
            $this->taxAmount1 = new Money($data['taxAmount1']['amount'], $data['taxAmount1']['code']);
        }
        if (isset($data['taxAmount2'])) {
            $this->taxAmount2 = new Money($data['taxAmount2']['amount'], $data['taxAmount2']['code']);
        }
        $this->taxName1 = $data['taxName1'] ?? null;
        $this->taxName2 = $data['taxName2'] ?? null;
        $this->taxPercent1 = $data['taxPercent1'] ?? null;
        $this->taxPercent2 = $data['taxPercent2'] ?? null;
        $this->transactionId = $data['transactionid'] ?? null;
        if (isset($data['updated'])) {
            $this->updated = Util::getAccountingDateTime($data['updated']);
        }
        $this->vendor = $data['vendor'] ?? null;
        $this->visState = $data['vis_state'] ?? null;
        if (isset($data['attachment'])) {
            $this->attachment = new ExpenseAttachment($data['attachment']);
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
        Util::convertContent($data, 'invoiceid', $this->invoiceId);
        Util::convertContent($data, 'accountid', $this->accountId);
        Util::convertContent($data, 'amount', $this->amount);
        Util::convertContent($data, 'bank_name', $this->bankName);
        Util::convertContent($data, 'billable', $this->billable);
        Util::convertContent($data, 'categoryid', $this->categoryId);
        Util::convertContent($data, 'clientid', $this->clientId);
        Util::convertContent($data, 'date', $this->date);
        Util::convertContent($data, 'ext_accountid', $this->extAccountId);
        Util::convertContent($data, 'ext_invoiceid', $this->extInvoiceId);
        Util::convertContent($data, 'ext_systemid', $this->extSystemId);
        Util::convertContent($data, 'has_receipt', $this->hasReceipt);
        Util::convertContent($data, 'include_receipt', $this->includeReceipt);
        Util::convertContent($data, 'is_cogs', $this->isCogs);
        Util::convertContent($data, 'isduplicate', $this->isDuplicate);
        Util::convertContent($data, 'markup_percent', $this->markupPercent);
        Util::convertContent($data, 'modern_projectid', $this->modernProjectId);
        Util::convertContent($data, 'notes', $this->notes);
        Util::convertContent($data, 'potential_bill_payment', $this->potentialBillPayment);
        Util::convertContent($data, 'projectid', $this->projectId);
        Util::convertContent($data, 'staffid', $this->staffId);
        Util::convertContent($data, 'taxAmount1', $this->taxAmount1);
        Util::convertContent($data, 'taxAmount2', $this->taxAmount2);
        Util::convertContent($data, 'taxName1', $this->taxName1);
        Util::convertContent($data, 'taxName2', $this->taxName2);
        Util::convertContent($data, 'vendor', $this->vendor);
        if (is_null($this->id) && is_null($this->expenseId)) {
            Util::convertContent($data, 'status', $this->status);
        }
        if (isset($this->date)) {
            $data['date'] = $this->date->format('Y-m-d');
        }
        Util::convertContent($data, 'attachment', $this->attachment);
        return $data;
    }
}
