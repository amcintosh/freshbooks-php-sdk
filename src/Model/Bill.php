<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use amcintosh\FreshBooks\Model\Caster\AccountingDateTimeImmutableCaster;
use amcintosh\FreshBooks\Model\Caster\DateCaster;
use amcintosh\FreshBooks\Model\Caster\MoneyCaster;
use DateTime;
use DateTimeImmutable;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Attributes\MapTo;
use Spatie\DataTransferObject\Casters\ArrayCaster;
use Spatie\DataTransferObject\DataTransferObject;

/**
 * Bills in FreshBooks are used to record a business transaction where the items and services
 * from a Vendor have been provided to the business owner, but payment isn't due until a later date.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/bills
 */
class Bill extends DataTransferObject implements DataModel
{
    public const RESPONSE_FIELD = 'bill';

    /**
     * @var int The unique identifier of this bill within this business.
     */
    public ?int $id;

    /**
     * @var Money Read-only. Computed from lines.
     */
    #[CastWith(MoneyCaster::class)]
    public ?Money $amount;

    /**
     * @var ExpenseAttachment Attached receipt.
     */
    public ?ExpenseAttachment $attachment;

    /**
     * @var string Reference to vendor bill number.
     */
    #[MapFrom('bill_number')]
    #[MapTo('bill_number')]
    public ?string $billNumber;

    /**
     * @var array Bill Payments made against the bill.
     */
    #[MapFrom('bill_payments')]
    #[MapTo('bill_payments')]
    #[CastWith(ArrayCaster::class, itemType: BillPayment::class)]
    public ?array $billPayments;

    /**
     * @var DateTimeImmutable Read-only. Time the invoice was created.
     */
    #[MapFrom('created_at')]
    #[CastWith(AccountingDateTimeImmutableCaster::class, isUtc: true)]
    public ?DateTimeImmutable $createdAt;

    /**
     * @var string Three-letter currency code.
     */
    #[MapFrom('currency_code')]
    #[MapTo('currency_code')]
    public ?string $currencyCode;

    /**
     * @var DateTimeImmutable Read-only. Date for which the bill is due for payment.
     */
    #[MapFrom('due_date')]
    #[MapTo('due_date')]
    #[CastWith(DateCaster::class)]
    public ?DateTime $dueDate;

    /**
     * @var int Number of days from the issue date that the invoice needs to be set to due.
     */
    #[MapFrom('due_offset_days')]
    #[MapTo('due_offset_days')]
    public ?int $dueOffsetDays;

    /**
     * @var DateTime Date when the bill was issued by the vendor.
     */
    #[MapFrom('issue_date')]
    #[MapTo('issue_date')]
    #[CastWith(DateCaster::class)]
    public ?DateTime $issueDate;

    /**
     * @var string Two-letter language code, e.g. "en".
     */
    public ?string $language;

    /**
     * @var array Array of bill line items.
     *
     * _Note:_ These are only returned with a bill call using a "lines" include.
     */
    #[CastWith(ArrayCaster::class, itemType: BillLine::class)]
    public ?array $lines;

    /**
     * @var Money Read-only. Computed from lines.
     */
    #[CastWith(MoneyCaster::class)]
    public ?Money $outstanding;

    /**
     * @var string Read-only. If multiple categories are selected in the bill lines, then overall_category is Split.
     * Otherwise, it will be the selected category.
     */
    #[MapFrom('overall_category')]
    public ?string $overallCategory;

    /**
     * @var string Read-only. First non-null value of bill line descriptions.
     */
    #[MapFrom('overall_description')]
    public ?string $overallDescription;

    /**
     * @var Money Read-only. Computed from lines.
     */
    #[CastWith(MoneyCaster::class)]
    public ?Money $paid;

    /**
     * @var string Read-only. Status of the bill: "unpaid", "overdue", "partial", "paid".
     */
    public ?string $status;

    /**
     * @var Money Read-only. Computed from lines.
     */
    #[MapFrom('tax_amount')]
    #[CastWith(MoneyCaster::class)]
    public ?Money $taxAmount;

    /**
     * @var Money Read-only. Computed from lines.
     */
    #[MapFrom('total_amount')]
    #[CastWith(MoneyCaster::class)]
    public ?Money $totalAmount;

    /**
     * @var DateTimeImmutable Read-only. Last time the resource was updated.
     */
    #[MapFrom('updated_at')]
    #[CastWith(AccountingDateTimeImmutableCaster::class, isUtc: true)]
    public ?DateTimeImmutable $updatedAt;

    /**
     * @var int Vendor id.
     */
    #[MapFrom('vendorid')]
    #[MapTo('vendorid')]
    public ?int $vendorId;

    /**
     * @var int 0 for active, 1 for deleted, 2 for archived.
     */
    #[MapFrom('vis_state')]
    #[MapTo('vis_state')]
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
            ->except('amount')
            ->except('createdAt')
            ->except('outstanding')
            ->except('overallCategory')
            ->except('overallDescription')
            ->except('paid')
            ->except('status')
            ->except('taxAmount')
            ->except('totalAmount')
            ->except('updatedAt')
            ->toArray();
        if (is_null($this->id)) {
            $data['status'] = $this->status;
        }
        if (isset($this->issueDate)) {
            $data['issue_date'] = $this->issueDate->format('Y-m-d');
        }
        if (isset($this->dueDate)) {
            $data['due_date'] = $this->dueDate->format('Y-m-d');
        }
        foreach ($data as $key => $value) {
            if (is_null($value)) {
                unset($data[$key]);
            }
        }
        return $data;
    }
}
