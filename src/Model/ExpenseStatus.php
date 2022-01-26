<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

/**
 * Status values for an expense.
 *
 * Values are:
 *
 * - **0:** INTERNAL. Internal rather than client
 *   This is a feature of FreshBooks Classic only and should only appear in migrated accounts.
 * - **1:** OUTSTANDING. Has client, needs to be applied to an invoice
 * - **2**: INVOICED. Has client, attached to an invoice
 * - **4**: RECOUPED. Has client, attached to an invoice, and paid
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/invoices
 */
class ExpenseStatus
{
    public const INTERNAL = 0;
    public const OUTSTANDING = 1;
    public const INVOICED = 2;
    public const RECOUPED = 4;
}
