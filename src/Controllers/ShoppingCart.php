<?php
declare(strict_types=1);

namespace Shopping\Controllers;

use Shopping\Models\ShoppingCartModel;
use Shopping\Views\ShoppingCartView;

/**
 * Class ShoppingCart
 * @package Shopping
 * @author David Sullivan
 *
 * A basic shopping cart. Written for version PHP v7.0
 * NOTE this class uses PHP Session storage. Session handling would
 * normally be outside this class to minimise coupling.
 */
class ShoppingCart
{
	public function __construct($sessionHandling = true)
	{
		// this should always be done first!
		if ($sessionHandling)
			session_start();

		$model = new ShoppingCartModel();
		$products = $model->productCosts();
		$url = $this->siteUrl();
		$valid = true;

		// Validate user request
		list($command, $item) = $this->parseQueryString();
		if ($command && $item)
		{
			switch ($command)
			{
				case 'add':     $valid = $model->add($item, 1);	break;
				case 'remove':  $valid = $model->remove($item);	break;
			}
		}

		// output view if request is valid
		if ($valid)
		{
			$view = new ShoppingCartView($url, $products, $model->selectedProducts());
			$view->listProducts();
			$view->listSelectedItems();
		}
		else
			error_log('Something went wrong: ' . $item . PHP_EOL);
	}

	protected function siteUrl(): string
	{
		static $url = 'http://';
		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
			$url = 'https://';
		}
		return $url . $_SERVER['HTTP_HOST'] . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	}

	protected function parseQueryString(): array
	{
		$query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
		$command = $item = null;

		// example query string: ?f=add&p=Bandsaw
		if ($query)
		{
			$qsa = explode('&', $query);
			if (count($qsa) > 1) {
				$commandQueryString = explode('=', $qsa[0]);
				$productQueryString = explode('=', $qsa[1]);

				$command = $commandQueryString[1];  // e.g. add
				$product = $productQueryString[1];  // e.g. Bandsaw

				// valid commands are 'add' or 'remove'
				if ($command == 'add' || $command == 'remove') {
					$item = $product;
				}
			}
		}
		return [$command, $item];
	}
}
