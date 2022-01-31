<?php
declare(strict_types=1);

namespace Shopping\Models;

/**
 * Class ShoppingCartModel
 * @package Shopping
 * @author David Sullivan
 *
 * A basic shopping cart.
 * NOTE this class uses PHP Session storage. Session handling would
 * normally be outside this class to minimise coupling.
 */
class ShoppingCartModel
{
	protected ?array $selectedProducts;

	protected static array $productCosts = [
		"Sledgehammer" => 125.75,
		"Axe" => 190.50,
		"Bandsaw"=> 562.131,
		"Chisel" => 12.9,
		"Hacksaw" => 18.45,
	];

	public function __construct()
    {
        $this->selectedProducts = null;
        if ((session_status() === PHP_SESSION_ACTIVE) && array_key_exists('selection', $_SESSION))
            $this->selectedProducts = unserialize($_SESSION['selection']);
    }

    /**
     * Get the product items
     * @return array
     */
    public function productCosts() :array
    {
        return self::$productCosts;
    }

    /**
	 * Get the items in the cart.
	 * @return array
	 */
	public function selectedProducts() :?array
	{
	    return $this->selectedProducts;
	}

	/**
	 * Find an item by name in the product list.
	 * @param string $itemName
	 * @return array|null
	 */
	public function productCost(string $itemName) :?float
	{
		foreach (self::$productCosts as $name => $cost) {
			if ($itemName == $name) {
				return (float)$cost;
			}
		}
		return null;
	}

	/**
	 * Add product to the cart
	 * @param string $itemName
	 * @param int $qty
	 *
	 * @return bool
	 */
	public function add(string $itemName, int $qty) :bool
	{
	    $cost = $this->productCost($itemName);
		if ($cost) {
            if ($this->selectedProducts && array_key_exists($itemName, $this->selectedProducts))
                $this->selectedProducts[$itemName] += $qty;
			else
				$this->selectedProducts[$itemName] = $qty;
			$_SESSION['selection'] = serialize($this->selectedProducts);
			return true;
		}
		return false;
	}

	/**
	 * Remove a product from the cart
	 * @param string $itemName
	 * @return bool
	 */
	public function remove(string $itemName) :bool
	{
        $cost = $this->productCost($itemName);
        if ($cost) {
			unset($this->selectedProducts[$itemName]);
			$_SESSION['selection'] = serialize($this->selectedProducts);
			return true;
		}
		return false;
	}
}
