<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\Casters\ArrayCaster;
use amcintosh\FreshBooks\Model\AccountingListLegacy;
use amcintosh\FreshBooks\Model\Callback;

/**
 * Results of callbacks list call containing list of callbacks and pagination data.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/webhooks
 */
class CallbackList extends AccountingListLegacy
{
    public const RESPONSE_FIELD = 'callbacks';

    #[CastWith(ArrayCaster::class, itemType: Callback::class)]
    public array $callbacks;
}
