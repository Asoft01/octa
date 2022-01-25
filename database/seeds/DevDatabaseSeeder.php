<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DevDatabaseSeeder extends Seeder {

	use TruncateTable;
	use DisableForeignKeys;

	/**
	 * Seed the application's database.
	 */
	public function run() {
		Model::unguard();
		$this->disableForeignKeys();

		$this->truncateMultiple([
			'cache',
			'failed_jobs',
			'ledgers',
			'jobs',
			'sessions'
		]);

		\App\Models\AcCategoryDomain::truncate();
		\App\Models\AcDomain::truncate();

		$ani = \App\Models\AcDomain::create([
				'announcements_category_id' => 19,
				'slug' => 'animation',
				'title' => 'Animation',
				'description' => 'Everything related to 3d animation.'
		]);

		$vfx = \App\Models\AcDomain::create([
				'announcements_category_id' => 20,
				'slug' => 'vfx',
				'title' => 'VFX',
				'description' => 'Everything related to visual effects.'
		]);

		foreach (\App\Models\AcCategory::get() as $category) {
			$now = now();
			if ($category->id % 2 != 0) {
				\App\Models\AcCategoryDomain::create([
					'ac_category_id' => $category->id,
					'ac_domain_id' => $ani->id,
					'created_at' => $now,
					'updated_at' => $now
				]);
			} else {
				\App\Models\AcCategoryDomain::create([
					'ac_category_id' => $category->id,
					'ac_domain_id' => $vfx->id,
					'created_at' => $now,
					'updated_at' => $now
				]);
			}
		}
		
		foreach (\App\Models\AcAccount::get() as $account) {
			$now = now();
			if ($account->id % 2 != 0) {
				$account->ac_domain_id = $ani->id;
				$account->save();
			} else {
				$account->ac_domain_id = $vfx->id;
				$account->save();
			}
		}

		foreach (\App\Models\AcTag::get() as $tag) {
			$now = now();
			if ($tag->id % 2 != 0) {
				$tag->domain_id = $ani->id;
				$tag->save();
			} else {
				$tag->domain_id = $vfx->id;
				$tag->save();
			}
		}

		$this->enableForeignKeys();
		Model::reguard();
	}

}
