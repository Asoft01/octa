<?php

namespace App\Console\Commands;

use App\Models\AcCategory;
use App\Models\AcDomain;
use Illuminate\Console\Command;

class AttachCategoryDomainCommand extends Command {

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'db:category-domain
		{categories?* : The category IDs. If empty, all categories will be used.}
		{--d|domain=default : The ID of the domain. If "default", the configured fallback domain ID will be used.}
	';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Attaches categories to a domain if they aren\'t already attached to that domain.';

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

		$category_ids = $this->argument('categories');
		$categories = empty($category_ids) ? AcCategory::get() : AcCategory::whereIn('id', $category_ids)->get();
		$categories->load('domains');
		
		$now = now();
		$count = 0;
		$timestamps = ['created_at' => $now, 'updated_at' => $now];

		if ($categories->isNotEmpty()) {
			foreach($categories as $category) {
				if ($category->domains->where('id', $domain->id)->isNotEmpty()) {
					$this->warn("Category {id: {$category->id}, title: \"{$category->title}\"} is already attached to domain {id: {$domain->id}, title: \"{$domain->title}\"}.");
				} else {
					$category->domains()->attach($domain->id, $timestamps);
					$this->info("Category {id: {$category->id}, title: \"{$category->title}\"} is now attached to domain {id: {$domain->id}, title: \"{$domain->title}\"}.");
					$count++;
				}
			}
			$this->line('');
		}

		$word = $count == 1 ? 'category' : 'categories';
		$this->line("Attached {$count} {$word} to domain {id: {$domain->id}, title: \"{$domain->title}\"}.");
		
		return 0;
	}

}
