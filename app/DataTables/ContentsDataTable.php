<?php

namespace App\DataTables;

use App\Models\AcVideo;
use App\Models\AcContent;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ContentsDataTable extends DataTable {
	/**
	 * Build DataTable class.
	 *
	 * @param mixed $query Results from query() method.
	 * @return \Yajra\DataTables\DataTableAbstract
	 */
	public function dataTable($query) {
		return datatables()
			->eloquent($query)
			->addColumn('action', '<a href="{{ route("frontend.user.preview", $slug) }}" class="btn btn-sm btn-secondary" title="Preview" target="_blank"><i class="fas fa-eye"></i></a><a href="#!" role="button" class="btn btn-sm btn-primary ml-1" data-content-id="{{ $id }}" title="Add to Playlist"><i class="fas fa-plus"></i></a>')
			->rawColumns(['action']);
	}

	/**
	 * Get query source of dataTable.
	 *
	 * @param \App\Order $model
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function query() {
		return AcContent::select([
			'ac_contents.id',
			'ac_contents.title',
			'ac_contents.slug',
			'ac_contents.cotd_start',
			'ac_contents.display_start',
			'ac_contents.contentable_type',
			'ac_category_contents.ac_category_id',
			'ac_categories.title as category'
		])
		->leftJoin('ac_category_contents', function($q) {
			$q->on('ac_category_contents.ac_content_id', '=', 'ac_contents.id');
		})
		->leftJoin('ac_categories', function($q) {
			$q->on('ac_category_contents.ac_category_id', '=', 'ac_categories.id');
		})
		->whereIn('ac_contents.contentable_type', ['MorphReview', 'MorphVideo'])
		->newQuery();
	}

	/**
	 * Optional method if you want to use html builder.
	 *
	 * @return \Yajra\DataTables\Html\Builder
	 */
	public function html() {
		return $this->builder()
			->setTableId('orders-table')
			->columns($this->getColumns())
			->minifiedAjax()
			->dom('Bfrtip')
			->orderBy(1, 'ASC')
			->parameters([
				"scrollY" =>        "600px",
				"scrollCollapse" => true,
				"paging" =>         true
			])
			->buttons(
				Button::make('reset'),
				Button::make('reload')
			);
	}

	/**
	 * Get columns.
	 *
	 * @return array
	 */
	protected function getColumns() {
		return [
			Column::make('id')->hidden(),
			Column::make('title')->name('ac_contents.title'),
			//Column::make('slug')->name('ac_contents.slug'),
			Column::make('category')->name('ac_categories.title'),
			Column::make('display_start')->name('ac_contents.display_start'),
			//Column::make('cotd_start')->name('ac_contents.cotd_start'),
			Column::computed('action')->exportable(false)->printable(false)
		];
	}

	/**
	 * Get filename for export.
	 *
	 * @return string
	 */
	protected function filename() {
		return 'Contents_' . date('YmdHis');
	}
}

