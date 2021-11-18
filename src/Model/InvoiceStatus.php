<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

/**
 * Visibility values for a resource. Mostly used in accounting-type resources, not project-type.
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
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/invoices
 */
class InvoiceStatus
{
    public const DISPUTED = 0;
    public const DRAFT = 1;
    public const SENT = 2;
    public const VIEWED = 3;
    public const PAID = 4;
    public const AUTOPAID = 5;
    public const RETRY = 6;
    public const FAILED = 7;
    public const PARTIAL = 8;
}
