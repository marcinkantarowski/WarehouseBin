<?php

require_once "Order.php";
require_once "BinOrder.php";
require_once "Bin.php";


/**
 * creating 2 Bins - bin1 and bin2
 */
$bin1 = new Bin();
$bin2 = new Bin();

/**
 * adding 5 Orders to bin1
 */
$bin1->addOrder(new Order(1, 10, 10));
$bin1->addOrder(new Order(2, 20, 15));
$bin1->addOrder(new Order(3, 30, 20));
$bin1->addOrder(new Order(4, 40, 22));
$bin1->addOrder(new Order(5, 10, 25));

/**
 * spoiling order1 and order3 in the bin1
 */
$bin1->spoilOrder(1);
$bin1->spoilOrder(3);

/**
 * showing the stock in the bin1
 */
echo "bin1 stock: <br>";
echo $bin1->showStock();

/**
 * calculating the value of the stock after removing some elements from the bin1
 */
echo $bin1->calculateStockValue(35);

/**
 * removing 35 elements from the stock - FIFO, showing the bin1 stock
 */
echo "<br>Now removing 35 elements from the bin1 - FIFO<br>";
$bin1->removeStock(35, 'FIFO');
echo "after removing bin1 stock:";
echo $bin1->showStock();

/**
 * moving 55 elements from bin1 to bin2 - LIFO, showing the bin1 and bin2 stock
 */
echo "<br>Now moving 55 elements from the bin1 to bin2 - LIFO<br>";
$bin1->moveStock(55, 'LIFO', $bin2);
echo "after moving<br><br>bin1 stock:";
echo $bin1->showStock();
echo "bin2 stock:";
echo $bin2->showStock();














