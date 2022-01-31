<?php
declare(strict_types=1);

namespace Shopping\Views;

/**
 * Class ShoppingCartView
 * @package Shopping
 * @author David Sullivan
 *
 */
class ShoppingCartView
{
	protected array $products;
	protected ?array $selectedProducts;
	protected string $url;

	public function __construct(string $url, array $products, ?array $selectedProducts)
	{
		$this->url = $url;
		$this->products = $products;
		$this->selectedProducts = $selectedProducts;
	}

	/**
	 * list all the products available for selection.
	 */
	public function listProducts()
	{
		$first = true;
		foreach ($this->products as $name => $cost) {
			if ($first) {
				$first = false;
				echo "<h1>Shopping Cart</h1>";
				echo "<h2>Product</h2>";
				echo "<table><thead><th>Name</th>";
				echo "<th>Cost</th>";
				echo "</thead>";
			}		
			echo "<tr>";
			echo "<td>" . $name . "</td>";
			echo "<td>" . number_format((float)$cost, 2) . "</td>";
			echo "<td>" . '<a href="' . $this->url . '?f=add&p=' . $name . '">Add</a></td>';
			echo "</tr>";
		}
		echo "</table>";
	}

	/**
	 * list all the products selected (in the cart).
	 */
	public function listSelectedItems()
	{
		if (!$this->selectedProducts)
		{
			error_log('No SELECTED FOUND!!!'. PHP_EOL);
			return;
		}

		$total = 0;
		$first = true;
		foreach ($this->selectedProducts as $name => $qty) {
			if ($first && $qty) {
				$first = false;
				echo "<h2>Selected Items</h2>";
				echo "<table><thead>";
				echo "<th>Name</th>";
				echo "<th>Price</th>";
				echo "<th>Quantity</th>";
				echo "<th>Total</th>";
				echo "</thead>";
			}
			if ($qty) {
				echo "<tr>";
				echo "<td>" . $name . "</td>";
				echo "<td>" . number_format($qty, 2) . "</td>";
				echo "<td>" . $qty . "</td>";
				$itemTotal = round($qty * (float)$this->products[$name], 2);
				$total += $itemTotal;
				echo "<td>" . number_format($itemTotal, 2) . "</td>";
				echo "<td>" . '<a href="' . $this->url . '?f=remove&p=' . $name . '">Remove</a></td>';
				echo "</tr>";
			}
		}
		if ($total > 0.005) {
			echo "<tr>";
			echo "<td><b>Overall Total</b></td>";
			echo "<td></td>";
			echo "<td></td>";
			echo "<td><b>" . number_format($total, 2) . "</b></td>";
			echo "</tr>";
		}
		echo "</table>";
	}
}
