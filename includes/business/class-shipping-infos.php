<?php

if(!defined('ABSPATH')){
    exit; // Exit if accessed directly
}

//https://woocommerce.github.io/code-reference/classes/WC-Order.html
//https://woocommerce.github.io/code-reference/classes/WC-Order-Item.html
//https://woocommerce.github.io/code-reference/classes/WC-Order-Item-Product.html
//https://woocommerce.github.io/code-reference/classes/WC-Product.html

class Shipping_Infos
{
    const TYPE_VIRTUAL = 'virtual';
    const TYPE_PHYSICAL = 'physical';
    const TYPE_MIXED = 'mixed';

    private $numVirtualItems = 0;
    private $numPhysicalItems = 0;
    private $numDownloadableItems = 0;
    private $numShippableProducts = 0;
    private $contentType;

    public function __construct(WC_Order $order)
    {
        $items = $order->get_items();
        $this->numVirtualItems = 0;
        $numTotal = 0;
        foreach($items as $item)
        {
            /** @var WC_Order_Item $item */

            $quantity = $item->get_quantity();
            $numTotal++;

            if($item->get_product()->get_virtual() == 1)
                $this->numVirtualItems++;
            else
                $this->numShippableProducts += $quantity;

            if(!empty($item->get_product()->get_downloadable()))
                $this->numDownloadableItems++;
        }
        $this->numPhysicalItems = $numTotal - $this->numVirtualItems;

        if($this->numVirtualItems > 0 && $this->numPhysicalItems > 0)
            $this->contentType = self::TYPE_MIXED;
        else if($this->numVirtualItems > 0)
            $this->contentType = self::TYPE_VIRTUAL;
        else
            $this->contentType = self::TYPE_PHYSICAL;

        /*
        echo 'Content type: '.$this->getContentType().'<br>';
        echo $this->getNumVirtualProduct().' virtual products<br>';
        echo $this->getNumPhysicalProduct().' physical products<br>';
        */
    }

    /**
     * @return bool
     */
    public function isVirtual():bool
    {
        return $this->numVirtualItems > 0;
    }

    /**
     * @return string
     */
    public function getContentType():string
    {
        return $this->contentType;
    }

    /**
     * @return int
     */
    public function getNumVirtualItems(): int
    {
        return $this->numVirtualItems;
    }

    /**
     * @return int
     */
    public function getNumPhysicalItems(): int
    {
        return $this->numPhysicalItems;
    }

    /**
     * @return int
     */
    public function getNumDownloadableItems(): int
    {
        return $this->numDownloadableItems;
    }

    /**
     * @return int
     */
    public function getNumShippableProducts(): int
    {
        return $this->numShippableProducts;
    }

    /**
     * @return bool
     */
    public function isShippable():bool
    {
        return $this->getNumShippableProducts() > 0;
    }

    /**
     * @return bool
     */
    public function isDownloadable():bool
    {
        return $this->getNumDownloadableItems() > 0;
    }
}