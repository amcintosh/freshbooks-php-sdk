<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

/**
 * Visibility values for a resource. Mostly used in accounting-type resources, not project-type.
 *
 * Values are:
 *
 * - *ACTIVE*: refers to objects that are both completed and non-completed.
 * - *DELETED*: objects are "soft-deleted" meaning they will not show up in list calls by default, and will not
 *   count towards finances, account limits, etc., but can be undeleted at any time.
 * - *ARCHIVED*: Are hidden from FreshBook's UI by default, but still count towards finances, account limits,
 *   etc.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/active_deleted
 */
class VisState
{
    public const ACTIVE = 0;
    public const DELETED = 1;
    public const ARCHIVED = 2;
}
