<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

use amcintosh\FreshBooks\Model\AccountingList;
use amcintosh\FreshBooks\Model\Tax;

/**
 * Results of taxes list call containing list of taxes and pagination data.
 *
 * @package amcintosh\FreshBooks\Model
 * @link https://www.freshbooks.com/api/taxes
 */
class TaxList extends AccountingList
{
    public array $taxes;

    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->taxes = $this->constructList($data['taxes'], Tax::class);
    }
}
