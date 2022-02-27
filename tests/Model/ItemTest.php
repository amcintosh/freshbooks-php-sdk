<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Tests\Model;

use DateTime;
use Spryker\DecimalObject\Decimal;
use PHPUnit\Framework\TestCase;
use amcintosh\FreshBooks\Model\Item;
use amcintosh\FreshBooks\Model\Money;
use amcintosh\FreshBooks\Model\VisState;

final class ItemTest extends TestCase
{
    private $sampleItemData = '{"item": {
        "accounting_systemid": "ACM123",
        "description": "Melmac melamine resin molded dinnerware",
        "id": 3456,
        "inventory": "50",
        "itemid": 3456,
        "name": "Bowls",
        "qty": "4",
        "sku": "SQ8608333",
        "tax1": 2,
        "tax2": 0,
        "unit_cost": {
            "amount": "4.00",
            "code": "CAD"
        },
        "updated": "2022-02-20 02:20:20",
        "vis_state": 0
    }}';

    public function testItemFromResponse(): void
    {
        $itemData = json_decode($this->sampleItemData, true);

        $item = new Item($itemData[Item::RESPONSE_FIELD]);

        $this->assertSame(3456, $item->id);
        $this->assertSame(3456, $item->itemId);
        $this->assertSame('ACM123', $item->accountingSystemId);
        $this->assertSame('Melmac melamine resin molded dinnerware', $item->description);
        $this->assertSame('50', $item->inventory);
        $this->assertSame('Bowls', $item->name);
        $this->assertSame(4, $item->quantity);
        $this->assertSame('SQ8608333', $item->sku);
        $this->assertSame(2, $item->tax1);
        $this->assertSame(0, $item->tax2);
        $this->assertEquals(Decimal::create('4.00'), $item->unitCost->amount);
        $this->assertSame('CAD', $item->unitCost->code);
        $this->assertEquals(new DateTime('2022-02-20T07:20:20Z'), $item->updated);
        $this->assertSame(VisState::ACTIVE, $item->visState);
    }

    public function testItemGetContent(): void
    {
        $itemData = json_decode($this->sampleItemData, true);
        $item = new Item($itemData['item']);
        $this->assertSame([
            'description' => 'Melmac melamine resin molded dinnerware',
            'inventory' => '50',
            'name' => 'Bowls',
            'qty' => 4,
            'sku' => 'SQ8608333',
            'tax1' => 2,
            'tax2' => 0,
            'unit_cost' => [
                'amount' => '4.00',
                'code' => 'CAD'
            ]
        ], $item->getContent());
    }
}
