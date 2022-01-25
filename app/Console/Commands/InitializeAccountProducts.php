<?php

namespace App\Console\Commands;

use App\Models\AcAccount;
use App\Models\AcCurrency;
use App\Models\AcDomain;
use App\Models\AcPrice;
use App\Models\AcProduct;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;


class InitializeAccountProducts extends Command {

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = "db:account-products
		{accounts?* : The account IDs. If empty, all accounts will be used}
		{--f|force : Answer \"yes\" to any confirmation prompts}
		{--d|domains=* : The domain IDs. If not present, all domains will be used}
		{--m|missing : Create missing products and prices for mentor accounts that already have products}
		{--o|overwrite : Overwrite products and prices for mentor accounts that already have products}
	";

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = "Creates products and prices for mentor accounts that have no products";

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
		$templates = $this->getTemplateProducts();
		// dump( $templates, $templates->count(), count(config('product.items')) ); return;
		$modifications = [];
		$count_deletes = 0;
		$count_inserts = 0;

		if ($this->option('overwrite') && !$this->confirmOverwrite()) return;
		if (empty($this->option('domains')) && !$this->confirmNoDomains()) return;

		$this->info("Selected {$accounts->count()} accounts.");
		$this->line("Working . . .");

		foreach ($accounts as $account) {
			if ($account->products->isEmpty()) {
				$inserts = $templates->where('domain_id', $account->ac_domain_id);
				if ($inserts->isNotEmpty()) {
					$this->createAccountProducts($account, $inserts->all());
					$modifications[] = $account->id;
					$count_inserts++;
				}
			}
			else if ($this->option('overwrite')) {
				$this->deleteAccountProducts($account);
				$count_deletes++;
				$inserts = $templates->where('domain_id', $account->ac_domain_id);
				if ($inserts->isNotEmpty()) {
					$this->createAccountProducts($account, $inserts->all());
					$modifications[] = $account->id;
					$count_inserts++;
				}
			}
			else if ($this->option('missing')) {
				$missing = $this->getMissingTemplateProducts($account, $templates);
				$inserts = $missing->where('domain_id', $account->ac_domain_id);
				if ($inserts->isNotEmpty()) {
					$this->createAccountProducts($account, $inserts->all());
					$modifications[] = $account->id;
					$count_inserts++;
				}
			}
		}

		$this->info("Deleted products and prices of {$count_deletes} accounts.");
		$this->info("Created products for {$count_inserts} accounts.");

		if (!empty($modifications)) {
			$cmd_name = 'db:account-prices';
			$cmd_params = ['accounts' => $modifications, '--force' => true, '--domains' => $this->option('domains')];
			$cmd_string = $this->getCommandString($cmd_name, $cmd_params);
			$this->line("Calling \"{$cmd_string}\" . . .");
			$this->call($cmd_name, $cmd_params);
		} else {
			$this->warn("No changes were made.");
		}
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
			->with(['products', 'products.prices'])
			->get();
	}

	protected function getTemplateProducts() {
		$currencies = AcCurrency::get();
		$domain_ids = $this->option('domains');
		$default = config('product.default');
		$items = config('product.items');
		$templates = [];

		foreach ($items as $item) {
			$template = Arr::except(array_merge($default, $item), ['account_id', 'internal_id']);
			if (empty($domain_ids) || in_array($template['domain_id'], $domain_ids)) {
				$base_currency = $currencies->where('id', $template['currency_id'])->first();
				$base_price = floatval($template['price']) / floatval($base_currency->multiplier);
				foreach ($currencies as $currency) {
					$templates[] = array_merge($template, [
						'currency_id' => $currency->id,
						'price' => $base_price * floatval($currency->multiplier)
					]);
				}
			}
		}

		return collect($templates)->when($this->option('domains'), function($collection, $domain_ids) {
			return $collection->whereIn('domain_id', $domain_ids);
		});
	}

	protected function getMissingTemplateProducts($account, $templates) {
		$filters = config('product.filters');

		return $templates->filter(function($template) use ($account, $filters) {
			$products = $account->products;
			foreach ($filters as $filter) {
				$products = $products->where($filter, $template[$filter]);
			}
			return $products->isEmpty();
		});
	}

	protected function createAccountProducts(&$account, $products) {
		Model::unguard();
		$inserted = $account->products()->createMany($products);
		Model::reguard();
		$account->setRelation('products', $account->products->concat($inserted));
	}

	protected function deleteAccountProducts(&$account) {
		$account->products->pluck('prices')->flatten()->each->delete();
		$account->products->each->delete();
		$account->setRelation('products', $account->products->take(0));
	}

	protected function getCommandString($command, $parameters) {
		$arguments = [];
		$options = [];

		foreach ($parameters as $key => $value) {
			if ($value === true) {
				$options[] = $key;
			}
			else if (!empty($value)) {
				if (substr($key, 0, 2) == '--') {
					$options[] = $key . '=' . implode(',', $value);
				} else {
					$arguments[] = implode(' ', $value);
				}
			}
		}
		
		return trim(implode(' ', array_merge(array($command), $arguments, $options)));
	}

	protected function confirmOverwrite() {
		$message = 'The overwrite option (--overwrite) is dangerous and may break foreign keys.';
		$confirm = $this->style($message, '<fg=yellow>') . PHP_EOL . ' Do you wish to continue?';

		if ($this->option('force')) {
			$this->warn($message);
			return true;
		}
		return $this->confirm($confirm);
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
