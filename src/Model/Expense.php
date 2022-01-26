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
use amcintosh\FreshBooks\Model\DataModel;
use amcintosh\FreshBooks\Model\Caster\AccountingDateTimeImmutableCaster;
use amcintosh\FreshBooks\Model\Caster\DateCaster;
use amcintosh\FreshBooks\Model\Caster\MoneyCaster;

/**
 * Expenses are used to track expenditures your business incurs.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/expenses
 */
class Expense extends DataTransferObject implements DataModel
{
    public const RESPONSE_FIELD = 'expense';

    /**
     * @var int The unique identifier of this expense within this business.
     */
    public ?int $id;

    /**
     * @var int Duplicate of id
     */
    #[MapFrom('expenseid')]
    public ?int $expenseId;

    /**
     * @var string Name of related account if applicable.
     */
    #[MapFrom('account_name')]
    #[MapTo('account_name')]
    public ?string $accountName;

    /**
     * @var int Id of expense account if applicable.
     */
    #[MapFrom('accountid')]
    #[MapTo('accountid')]
    public ?int $accountId;

    /**
     * @var string Unique identifier of business the expense exists on.
     */
    #[MapFrom('accounting_systemid')]
    public ?string $accountingSystemId;

    /**
     * @var Money Total amount of invoice
     *
     * Money object containing amount and currency code.
     */
    #[CastWith(MoneyCaster::class)]
    public ?Money $amount;

    /**
     * @var string Name of bank expense was imported from, if applicable.
     */
    #[MapFrom('bank_name')]
    #[MapTo('bank_name')]
    public ?string $bankName;

    /**
     * @var bool Can the expense be billed to a Client or Project.
     */
    public ?bool $billable;

    /**
     * @var int Id of the expense category.
     */
    #[MapFrom('categoryid')]
    #[MapTo('categoryid')]
    public ?int $categoryId;

    /**
     * @var int Id of client the expense has been assigned to if applicable.
     */
    #[MapFrom('clientid')]
    #[MapTo('clientid')]
    public ?int $clientId;

    /**
     * @var DateTime Date of the expense.
     *
     * The API returns this in YYYY-MM-DD format. It is converted to a DateTime.
     */
    #[CastWith(DateCaster::class)]
    public ?DateTime $date;

    /**
     * @var int The id of related contractor account if applicable.
     */
    #[MapFrom('ext_accountid')]
    #[MapTo('ext_accountid')]
    public ?int $extAccountId;

    /**
     * @var int The id of related contractor invoice if applicable.
     */
    #[MapFrom('ext_invoiceid')]
    #[MapTo('ext_invoiceid')]
    public ?int $extInvoiceId;

    /**
     * @var int The id of related contractor system if applicable.
     */
    #[MapFrom('ext_systemid')]
    #[MapTo('ext_systemid')]
    public ?int $extSystemId;

    /**
     * @var bool Indicates if the expense was created via a bulk import action.
     */
    #[MapFrom('from_bulk_import')]
    public ?bool $fromBulkImport;

    /**
     * @var bool Indicates if the expense has an attached receipt.
     */
    #[MapFrom('has_receipt')]
    #[MapTo('has_receipt')]
    public ?bool $hasReceipt;

    #[MapFrom('include_receipt')]
    #[MapTo('include_receipt')]
    public ?bool $includeReceipt;

    /**
     * @var int The id of related invoice if applicable.
     */
    #[MapFrom('invoiceid')]
    #[MapTo('invoiceid')]
    public ?int $invoiceId;

    /**
     * @var bool If the expense counts as "Cost of Goods Sold" and is associated with a client.
     */
    #[MapFrom('is_cogs')]
    #[MapTo('is_cogs')]
    public ?bool $isCogs;

    /**
     * @var bool If the expense is a duplicated expense.
     */
    #[MapFrom('isduplicate')]
    #[MapTo('isduplicate')]
    public ?bool $isDuplicate;

    /**
     * @var string Note of percent to mark expense up.
     */
    #[MapFrom('markup_percent')]
    #[MapTo('markup_percent')]
    public ?string $markupPercent;

    /**
     * @var int Id of related new FreshBooks (not Classic) project if applicable.
     */
    #[MapFrom('modern_projectid')]
    #[MapTo('modern_projectid')]
    public ?int $modernProjectId;

    /**
     * @var string Notes about the expense.
     */
    public ?string $notes;

    #[MapFrom('potential_bill_payment')]
    #[MapTo('potential_bill_payment')]
    public ?bool $potentialBillPayment;

    /**
     * @var int Id of related recurring expense profile if applicable.
     */
    #[MapFrom('profileid')]
    public ?int $profileId;

    /**
     * @var int Id of related FreshBooks Classic project if applicable.
     */
    #[MapFrom('projectid')]
    #[MapTo('projectid')]
    public ?int $projectId;

    /**
     * @var int The id of related staff member if applicable.
     */
    #[MapFrom('staffid')]
    #[MapTo('staffid')]
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
    #[CastWith(MoneyCaster::class)]
    public ?Money $taxAmount1;

    /**
     * @var Money The amount of the second tax making up the total amount of the expense.
     *
     * Money object containing amount and currency code.
     */
    #[CastWith(MoneyCaster::class)]
    public ?Money $taxAmount2;

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
    #[MapFrom('transactionid')]
    #[MapTo('transactionid')]
    public ?int $transactionId;

    /**
     * @var DateTimeImmutable The time of last modification.
     */
    #[CastWith(AccountingDateTimeImmutableCaster::class)]
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
    #[MapFrom('vis_state')]
    public ?int $visState;

    // Includes
    // ExpenseAttachment attachment

    /**
     * Get the data as an array to POST or PUT to FreshBooks, removing any read-only fields.
     *
     * @return array
     */
    public function getContent(): array
    {
        $data = $this
            ->except('id')
            ->except('expenseId')
            ->except('accountingSystemId')
            ->except('fromBulkImport')
            ->except('profileId')
            ->except('status')
            ->except('updated')
            ->except('visState')
            ->toArray();
        if (is_null($this->id) && is_null($this->expenseId)) {
            $data['status'] = $this->status;
        }
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
