<?php

namespace App\Console\Commands;

use App\Models\AcAccount;
use App\Models\AcCurrency;
use App\Models\AcPrice;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class UpdateAccountPrices extends Command {

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = "db:account-prices
		{accounts?* : The account IDs. If empty, all accounts will be used}
		{--f|force : Answer \"yes\" to any confirmation prompts}
		{--d|domains=* : The domain IDs. If not present, all domains will be used}
	";

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = "For the specified accounts, updates AcProducts price and AcPrices based on similar USD AcProducts.";

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		$accounts = $this->getSpecifiedAccounts();
		$products = $accounts->pluck('products')->flatten();

		$count_accounts = $accounts->count();
		$count_products = $products->count();
		$count_currencies = $products->pluck('currency')->flatten()->unique()->count();
		$count_prices = 0;

		if (empty($this->option('domains')) && !$this->confirmNoDomains()) return;

		$this->info("Found {$count_accounts} accounts, {$count_products} products, {$count_currencies} currencies.");
		$this->line("Working . . .");

		$base_currency = AcCurrency::where('iso', 'USD')->first();

		foreach ($products->where('currency_id', $base_currency->id) as $base_product) {
			foreach ($this->getSimilarProducts($base_product, $products) as $similar_product) {
				$similar_product->price = floatval($base_product->price) * floatval($similar_product->currency->multiplier);
				$similar_product->save();

				$this->updateOrCreateAcPrice($similar_product);
				$base_product->account->touch();
				$count_prices++;
			}

			$this->updateOrCreateAcPrice($base_product);
			$base_product->account->touch();
			$count_prices++;
		}

		$this->info("Updated/created {$count_prices} prices ({$count_accounts} accounts, {$count_products} products, {$count_currencies} currencies).");

		//$this->line(sprintf('%0.2f %s * %0.2f = %0.2f %s', $value, $base, $c->multiplier, $value * $c->multiplier, $c->iso));
	}

	protected function getSpecifiedAccounts() {
		return AcAccount::query()
			->when($this->argument('accounts'), function($q, $account_ids) {
				return $q->whereIn($q->qualifyColumn('id'), $account_ids);
			})
			->when($this->option('domains'), function($q, $domain_ids) {
				return $q->whereIn($q->qualifyColumn('ac_domain_id'), $domain_ids);
			})
			->whereHas('user.roles', function($q) {
				return $q->where($q->qualifyColumn('name'), 'mentor');
			})
			->with(['products', 'products.currency'])
			->get();
	}

	protected function getSimilarProducts($product, $collection, $except = ['id', 'currency_id', 'price', 'created_at', 'updated_at']) {
		$attributes = Arr::except($product->getAttributes(), $except);
		return $collection
			->where('id', '!=', $product->id)
			->where('currency_id', '!=', $product->currency_id)
			->filter(function($item) use ($attributes) {
				foreach ($attributes as $key => $value) {
					if ($item->{$key} != $value) return false;
				}
				return true;
			});
	}

	protected function updateOrCreateAcPrice($product) {
		Model::unguard();
		$model = AcPrice::updateOrCreate([
			'account_id' => $product->account_id,
			'currency_id' => $product->currency_id,
			'product_id' => $product->id,
			'isActive' => 1,
			'start_date' => null,
			'end_date' => null
		], [
			'price' => $product->price,
		]);
		Model::reguard();
		return $model;
	}

	protected function confirmNoDomains() {
		$message = 'You have not specified any domains (--domains[=DOMAINS]).';
		$confirm = $this->style($message, '<fg=yellow>') . PHP_EOL . ' Do you wish to continue?';

		if ($this->option('force')) {
			$this->warn($message);
			return true;
		}
		return $this->confirm($confirm);
	}

	protected function style($message, $open, $close = '</>') {
		return $this->option('no-ansi') ? $message : $open . $message . $close;
	}

	protected function isVerbosity($verbosity = null) {
		return $this->getOutput()->getVerbosity() >= $this->parseVerbosity($verbosity);
	}
}
