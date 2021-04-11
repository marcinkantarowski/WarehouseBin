<?php

require_once "Order.php";
require_once "BinOrder.php";
require_once "Bin.php";


use PHPUnit\Framework\TestCase;

class BinTest extends TestCase
{

    public function testAddOrder_Id_Quantity_Price(): void
    {
        $bin = new Bin();
        $order = new Order(1, 10, 20);
        $bin->addOrder($order);
        $this->assertEquals(1,$order->getId());
        $this->assertEquals(10,$order->getQuantity());
        $this->assertEquals(20,$order->getPrice());
    }

    public function testSpoilOrder_spoiled_notspoiled(): void
    {
        //arrange
        $bin = new Bin();
        $order1 = new Order(1, 10, 20);
        $order2 = new Order(2, 10, 20);
        $bin->addOrder($order1);
        $bin->addOrder($order2);
        $stock = $bin->getStock();

        //act
        $bin->spoilOrder(1);
        $actualspoiled=false;
        $actualnotspoiled= false;

        foreach ($stock as $binorder) {
            if ($binorder->getId() == 1) $actualspoiled=$binorder->getSpoiled();
            if ($binorder->getId() == 2) $actualnotspoiled=$binorder->getSpoiled();
        }

        //assert
        $this->assertEquals(true,$actualspoiled);
        $this->assertEquals(false,$actualnotspoiled);
    }

    public function testCalculateTotalQuantity(): void
    {
        $bin = new Bin();
        $order1 = new Order(1, 1, 5);
        $order2 = new Order(1, 2, 10);
        $order3 = new Order(1, 10, 20);
        $order4 = new Order(1, 15, 20);
        $bin->addOrder($order1);
        $bin->addOrder($order2);
        $bin->addOrder($order3);
        $bin->addOrder($order4);
        $this->assertEquals(28,$bin->calculateTotalQuantity());
    }

    public function testcalculateTotalValue_Value_isFloat(): void
    {
        $bin = new Bin();
        $order1 = new Order(1, 1, 2);
        $order2 = new Order(1, 2, 4);
        $order3 = new Order(1, 3, 1);
        $order4 = new Order(1, 4, 5);
        $bin->addOrder($order1);
        $bin->addOrder($order2);
        $bin->addOrder($order3);
        $bin->addOrder($order4);
        $this->assertEquals(33,$bin->calculateTotalValue());
        $this->assertIsFloat($bin->calculateTotalValue());
    }

    public function testRemoveStock_FIFO_Qantity_Value(): void
    {
        $bin = new Bin();
        $order1 = new Order(1, 1, 5);
        $order2 = new Order(1, 2, 10);
        $order3 = new Order(1, 10, 20);
        $order4 = new Order(1, 15, 20);
        $bin->addOrder($order1);
        $bin->addOrder($order2);
        $bin->addOrder($order3);
        $bin->addOrder($order4);
        $bin->removeStock(3,'FIFO');

        $this->assertEquals(25,$bin->calculateTotalQuantity());
        $this->assertEquals(500,$bin->calculateTotalValue());
    }

    public function testRemoveStock_LIFO_Qantity_Value(): void
    {
        $bin = new Bin();
        $order1 = new Order(1, 1, 5);
        $order2 = new Order(1, 2, 10);
        $order3 = new Order(1, 10, 20);
        $order4 = new Order(1, 15, 20);
        $bin->addOrder($order1);
        $bin->addOrder($order2);
        $bin->addOrder($order3);
        $bin->addOrder($order4);
        $bin->removeStock(3,'LIFO');
        $this->assertEquals(25,$bin->calculateTotalQuantity());
        $this->assertEquals(465,$bin->calculateTotalValue());
    }

    public function testMoveStock_FIFO_Quantity_Value(): void
    {
        $bin = new Bin();
        $bin2 = new Bin();
        $order1 = new Order(1, 1, 5);
        $order2 = new Order(1, 2, 10);
        $order3 = new Order(1, 10, 20);
        $order4 = new Order(1, 15, 20);
        $bin->addOrder($order1);
        $bin->addOrder($order2);
        $bin->addOrder($order3);
        $bin->addOrder($order4);
        $bin->moveStock(13,'FIFO',$bin2);

        $this->assertEquals(15,$bin->calculateTotalQuantity());
        $this->assertEquals(300,$bin->calculateTotalValue());
        $this->assertEquals(13,$bin2->calculateTotalQuantity());
        $this->assertEquals(225,$bin2->calculateTotalValue());
    }

    public function testMoveStock_LIFO_Quantity_Value(): void
    {
        $bin = new Bin();
        $bin2 = new Bin();
        $order1 = new Order(1, 1, 5);
        $order2 = new Order(1, 2, 10);
        $order3 = new Order(1, 10, 20);
        $order4 = new Order(1, 15, 20);
        $bin->addOrder($order1);
        $bin->addOrder($order2);
        $bin->addOrder($order3);
        $bin->addOrder($order4);
        $bin->moveStock(13,'LIFO',$bin2);

        $this->assertEquals(15,$bin->calculateTotalQuantity());
        $this->assertEquals(265,$bin->calculateTotalValue());
        $this->assertEquals(13,$bin2->calculateTotalQuantity());
        $this->assertEquals(260,$bin2->calculateTotalValue());
    }

    public function testCalculateValue_FIFO_LIFO_AVERAGE_isFloat(): void
    {
        $bin = new Bin();
        $bin->addOrder(new Order(1, 10, 10));
        $bin->addOrder(new Order(2, 20, 15));
        $bin->addOrder(new Order(3, 30, 20));
        $bin->addOrder(new Order(4, 40, 22));
        $bin->addOrder(new Order(5, 10, 25));

        $this->assertEquals(1630,$bin->calculateValue(35,'FIFO'));
        $this->assertEquals(1330,$bin->calculateValue(35,'LIFO'));
        $this->assertEquals(1452.2727272727,$bin->calculateValue(35,'AVERAGE'));
        $this->assertIsFloat($bin->calculateValue(35,'LIFO'));
    }
}
