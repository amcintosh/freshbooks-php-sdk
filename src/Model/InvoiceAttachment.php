<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use amcintosh\FreshBooks\Model\DataModel;
use amcintosh\FreshBooks\Util;

/**
 * Attached files and images to include with an invoice.
 *
 * _Note:_ This data is not in the default response and will only be
 * present with the use of a corresponding "includes" filter.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/invoice_presentation_attachments
 */
class InvoiceAttachment implements DataModel
{
    /**
     * @var int The unique identifier of this expense attachment within this business.
     */
    public ?int $id;

    /**
     * @var int Duplicate of id
     */
    public ?int $attachmentId;

    /**
     * @var int Id of the expense this attachment is associated with, if applicable.
     */
    public ?int $expenseId;

    /**
     * @var string JWT link to the attachment.
     */
    public ?string $jwt;

    /**
     * @var string Type of the attachment.
     */
    public ?string $mediaType;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->attachmentId = $data['attachmentid'] ?? null;
        $this->expenseId = $data['expenseid'] ?? null;
        $this->jwt = $data['jwt'] ?? null;
        $this->mediaType = $data['media_type'] ?? null;
    }

    /**
     * Get the data as an array to POST or PUT to FreshBooks, removing any read-only fields.
     *
     * @return array
     */
    public function getContent(): array
    {
        $data = array();
        Util::convertContent($data, 'expenseid', $this->expenseId);
        Util::convertContent($data, 'jwt', $this->jwt);
        Util::convertContent($data, 'media_type', $this->mediaType);
        return $data;
    }
}
