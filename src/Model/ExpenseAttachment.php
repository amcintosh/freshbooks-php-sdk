<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Attributes\MapTo;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\DataTransferObject;
use amcintosh\FreshBooks\Model\DataModelLegacy;

/**
 * Attached receipt image details for an expense.
 *
 * _Note:_ This data is not in the default response and will only be
 * present with the use of a corresponding "includes" filter.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/expense-attachments
 */
class ExpenseAttachment extends DataTransferObject implements DataModelLegacy
{
    public const RESPONSE_FIELD = 'expense';

    /**
     * @var int The unique identifier of this expense attachment within this business.
     */
    public ?int $id;

    /**
     * @var int Duplicate of id
     */
    #[MapFrom('attachmentid')]
    public ?int $attachmentId;

    /**
     * @var string JWT link to the attachment.
     */
    public ?string $jwt;

    /**
     * @var string Type of the attachment.
     */
    #[MapFrom('media_type')]
    #[MapTo('media_type')]
    public ?string $mediaType;

    /**
     * Get the data as an array to POST or PUT to FreshBooks, removing any read-only fields.
     *
     * @return array
     */
    public function getContent(): array
    {
        $data = $this
            ->except('id')
            ->except('attachmentId')
            ->toArray();
        foreach ($data as $key => $value) {
            if (is_null($value)) {
                unset($data[$key]);
            }
        }
        return $data;
    }
}
