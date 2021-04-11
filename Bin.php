<?php


class Bin
{
    /**
     * @var BinOrder array
     */
    private array $stock = [];
    private int $binorderid = 1;

    /**
     * adds Order to the Bin
     * @param Order $order
     */
    public function addOrder(Order $order): void
    {
        $binorder = new BinOrder($this->binorderid, $order->getId(), $order->getQuantity(), $order->getPrice());
        $this->stock[$this->binorderid] = $binorder;
        $this->binorderid++;
    }

    /**
     * spoils the Order in the Bin
     * @param int $orderid
     */
    public function spoilOrder(int $orderid): void
    {
        foreach ($this->stock as $binorder) {
            if ($binorder->getId() == $orderid) $binorder->setSpoiled(true);
        }
    }

    /**
     * calculates total quantity of the stock in the Bin
     * @return int
     */
    public function calculateTotalQuantity(): int
    {
        $quantity = 0;
        foreach ($this->stock as $binorder) {
            $quantity += $binorder->getQuantity();
        }
        return $quantity;
    }

    /**
     * calculates total value of the stock in the Bin
     * @return float
     */
    public function calculateTotalValue(): float
    {
        $value = 0;
        foreach ($this->stock as $binorder) {
            $value += $binorder->getQuantity() * $binorder->getPrice();
        }
        return $value;
    }

    /**
     * removes some stock from the bin - two methods available - FIFO & LIFO
     * @param int $howmany
     * @param string $method e.g. FIFO/LIFO
     */
    public function removeStock(int $howmany, string $method): void
    {
        $orderarray = $this->stock; //FIFO default
        if ($method == "FIFO") $orderarray = $this->stock;
        if ($method == "LIFO") $orderarray = array_reverse($this->stock);

        $left = $howmany;
        foreach ($orderarray as $binorder) {
            if ($binorder->getQuantity() <= $left) {
                $left = $left - $binorder->getQuantity();
                unset($this->stock[$binorder->getBinOrderId()]);
            } else {
                if ($left > 0) {
                    $binorder->setQuantity($binorder->getQuantity() - $left);
                    if ($left < $binorder->getQuantity()) $left = $left - $binorder->getQuantity();
                    else $left = 0;
                }
            }
        }
    }

    /**
     * moves the stock from one Bin to the other - two methods available - FIFO & LIFO
     * @param int $howmany
     * @param string $method e.g. FIFO/LIFO
     * @param Bin $newbin
     */
    public function moveStock(int $howmany, string $method, Bin $newbin): void
    {
        $orderarray = $this->stock; //FIFO default
        if ($method == "FIFO") $orderarray = $this->stock;
        if ($method == "LIFO") $orderarray = array_reverse($this->stock);

        $left = $howmany;
        foreach ($orderarray as $binorder) {
            if ($binorder->getQuantity() <= $left) {
                $newbin->addOrder(new Order($binorder->getId(), $binorder->getQuantity(), $binorder->getPrice()));
                if ($binorder->getSpoiled()) $newbin->spoilOrder($binorder->getId());
                $left = $left - $binorder->getQuantity();
                unset($this->stock[$binorder->getBinOrderId()]);
            } else {
                if ($left > 0) {
                    $binorder->setQuantity($binorder->getQuantity() - $left);
                    $newbin->addOrder(new Order($binorder->getId(), $left, $binorder->getPrice()));
                    if ($binorder->getSpoiled()) $newbin->spoilOrder($binorder->getId());
                    if ($left < $binorder->getQuantity()) $left = $left - $binorder->getQuantity();
                    else $left = 0;
                }
            }
        }
    }

    /**
     * calculates the value of the stock in the Bin using FIFO,LIFO,AVERAGE methods
     * @param int $howmany
     * @param string $method e.g. FIFO/LIFO/AVERAGE
     * @return float
     */
    public function calculateValue(int $howmany, string $method): float
    {
        if ($method == "AVERAGE") {
            $quantityinit = $this->calculateTotalQuantity();
            $valueinit = $this->calculateTotalValue();
            $averageprice = $valueinit / $quantityinit;
            $value = ($quantityinit - $howmany) * $averageprice;
        } else {
            $stockarray = $this->copyValues(); //FIFO default
            if ($method == "LIFO") $stockarray = array_reverse($this->copyValues());
            $left = $howmany;
            foreach ($stockarray as $i => $binorder) {
                if ($binorder['quantity'] <= $left) {
                    $left = $left - $binorder['quantity'];
                    unset($stockarray[$i]);
                } else {
                    if ($left > 0) {
                        $stockarray[$i]['quantity'] = $binorder['quantity'] - $left;
                        if ($left < $binorder['quantity']) $left = $left - $binorder['quantity'];
                        else $left = 0;
                    }
                }
            }
            $value = 0;
            foreach ($stockarray as $binorder) {
                $value += $binorder['quantity'] * $binorder['price'];
            }
        }

        return $value;
    }

    /**
     * visualisation of the differences
     * @param int $howmany
     * @return string
     */
    public function calculateStockValue(int $howmany): string
    {
        $averagevalue = $this->calculateValue($howmany, 'AVERAGE');
        $fifovalue = $this->calculateValue($howmany, 'FIFO');
        $lifovalue = $this->calculateValue($howmany, 'LIFO');

        $out = "The difference between calculating the stock value - Average, FIFO, LIFO<br>" .
            "After removing $howmany elements from the stock:<br>" .
            "AVERAGE VALUE: $averagevalue<br>" .
            "FIFO VALUE: $fifovalue<br>" .
            "LIFO VALUE: $lifovalue<br>";

        return $out;
    }

    /**
     * visualization of the Bin
     * @return string
     */
    public function showStock(): string
    {
        $htmlstock = "<div><table><tr><th>order id</th><th>order quantity</th><th>order price</th><th>order spoiled</th></tr>";
        foreach ($this->stock as $binorder) {
            $id = $binorder->getId();
            $quantity = $binorder->getQuantity();
            $price = $binorder->getPrice();
            $spoiled = $binorder->getSpoiled();
            $htmlstock .= "<tr><td>$id</td><td>$quantity</td><td>$price</td><td>$spoiled</td></tr>";
        }

        $value = $this->calculateTotalValue();
        $htmlstock .= "</table></div> Total Value: $value<br><br>";

        return $htmlstock;
    }

    /**
     * getter
     * @return BinOrder
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * copies the values - id, quantity, price to the array
     * @return array
     */
    private function copyValues(): array
    {
        $values = [];
        foreach ($this->stock as $binorder) {
            $values[$binorder->getBinOrderId()] = array("id" => $binorder->getId(), "quantity" => $binorder->getQuantity(), "price" => $binorder->getPrice());
        }
        return $values;
    }

}

