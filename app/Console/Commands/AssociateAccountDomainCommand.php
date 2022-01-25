<?php

namespace App\Console\Commands;

use App\Models\AcAccount;
use App\Models\AcDomain;
use Illuminate\Console\Command;

class AssociateAccountDomainCommand extends Command {

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'db:account-domain
		{accounts?* : The account IDs. If empty, all accounts will be used.}
		{--d|domain=default : The ID of the domain. If "default", the configured fallback domain ID will be used.}
	';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Updates accounts to include specified ac_domain_id if they don\'t already have one.';

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
		$domain_id = $this->option('domain') === 'default' ? config('app.fallback_domain_id') : $this->option('domain');
		$domain = AcDomain::findOrFail($domain_id);

		$account_ids = $this->argument('accounts');
		$accounts = empty($account_ids) ? AcAccount::get() : AcAccount::whereIn('id', $account_ids)->get();
		$accounts->load('domain');
		
		$count = 0;

		if ($accounts->isNotEmpty()) {
			foreach($accounts as $account) {
				if ($account->ac_domain_id !== null) {
					if (is_model($account->domain)) {
						$this->warn("Account {id: {$account->id}, slug: \"{$account->slug}\"} already belongs to domain {id: {$account->domain->id}, title: \"{$account->domain->title}\"}.");
					} else {
						$this->error("Account {id: {$account->id}, slug: \"{$account->slug}\"} already belongs to domain {id: {$account->ac_domain_id}}, but that domain is missing!");
					}
				} else {
					$account->domain()->associate($domain->id);
					$account->save();
					$account->touch();
					$this->info("Account {id: {$account->id}, slug: \"{$account->slug}\"} now belongs to domain {id: {$domain->id}, title: \"{$domain->title}\"}.");
					$count++;
				}
			}
			$this->line('');
		}

		$word = $count == 1 ? 'account' : 'accounts';
		$this->line("Updated {$count} {$word} to belong to domain {id: {$domain->id}, title: \"{$domain->title}\"}.");
		
		return 0;
	}

}
