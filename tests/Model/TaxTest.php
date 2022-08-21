<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Model;

use DateTime;
use DateTimeZone;
use PHPUnit\Framework\TestCase;
use amcintosh\FreshBooks\Model\Tax;
use amcintosh\FreshBooks\Model\VisState;

final class TaxTest extends TestCase
{
    private $sampleTaxData = '{"tax":{
        "accounting_systemid": "ACM123",
        "amount": "13",
        "compound": false,
        "id": 7840,
        "name": "HST",
        "number": "RT 1234",
        "taxid": 7840,
        "updated": "2020-06-16 10:04:37"}}';

    public function testTaxFromResponse(): void
    {
        $taxData = json_decode($this->sampleTaxData, true);

        $tax = new Tax($taxData[Tax::RESPONSE_FIELD]);

        $this->assertSame(7840, $tax->id);
        $this->assertSame('ACM123', $tax->accountingSystemId);
        $this->assertSame('13', $tax->amount);
        $this->assertSame('HST', $tax->name);
        $this->assertSame('RT 1234', $tax->number);
        $this->assertEquals(new DateTime('2020-06-16T14:04:37Z'), $tax->updated);
    }

    public function testTaxGetContent(): void
    {
        $taxData = json_decode($this->sampleTaxData, true);
        $tax = new Tax($taxData['tax']);
        $this->assertSame([
            'amount' => '13',
            'name' => 'HST',
            'number' => 'RT 1234'
        ], $tax->getContent());
    }
}
