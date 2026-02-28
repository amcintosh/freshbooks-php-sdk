<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use amcintosh\FreshBooks\Model\AccountingList;
use amcintosh\FreshBooks\Model\Invoice;

/**
 * Results of invoices list call containing list of invoices and pagination data.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/invoices
 */
class InvoiceList extends AccountingList
{
    public array $invoices;

    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->invoices = $this->constructList($data['invoices'], Invoice::class);
    }
}
