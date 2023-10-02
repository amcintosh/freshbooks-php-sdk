<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use DateTimeImmutable;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\Attributes\MapTo;
use Spatie\DataTransferObject\Caster;
use Spatie\DataTransferObject\DataTransferObject;
use amcintosh\FreshBooks\Model\Caster\ISODateTimeImmutableCaster;
use amcintosh\FreshBooks\Model\DataModel;

/**
 * Webhook callback subscription model.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/webhooks
 */
class Callback extends DataTransferObject implements DataModel
{
    public const RESPONSE_FIELD = 'callback';

    /**
     * @var int Get the unique identifier of this callback within this business.
     */
    #[MapFrom('callbackid')]
    public ?int $callbackId;

    /**
     * @var string The event to register the webhook callback for (eg. `invoice.create`).
     */
    public ?string $event;

    /**
     * @var DateTimeImmutable The time of last modification.
     */
    #[MapFrom('updated_at')]
    #[CastWith(ISODateTimeImmutableCaster::class)]
    public ?DateTimeImmutable $updatedAt;

    /**
     * @var string The URI to send the webhook callback to.
     */
    public ?string $uri;

    /**
     * @var bool Whether the callback has been verified against the URI.
     */
    public ?bool $verified;

    /**
     * Get the data as an array to POST or PUT to FreshBooks, removing any read-only fields.
     *
     * @return array
     */
    public function getContent(): array
    {
        $data = $this
            ->except('callbackId')
            ->except('updatedAt')
            ->except('verified')
            ->toArray();
        foreach ($data as $key => $value) {
            if (is_null($value)) {
                unset($data[$key]);
            }
        }
        return $data;
    }
}
