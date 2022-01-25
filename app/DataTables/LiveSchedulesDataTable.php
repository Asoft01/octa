<?php

namespace App\DataTables;

use App\Models\AcLiveSchedule;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\DB;

class LiveSchedulesDataTable extends DataTable {

	/**
	 * Build DataTable class.
	 *
	 * @param mixed $query Results from query() method.
	 * @return \Yajra\DataTables\DataTableAbstract
	 */
	public function dataTable($query) {
		return datatables()
			->eloquent($query)
			->addColumn('action', '<a href="'.route('admin.library.schedules.edit').'/{{$id}}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> Edit</a>')
            ->rawColumns(['action']);
	}

	/**
	 * Get query source of dataTable.
	 *
	 * @param \App\Order $model
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function query() {
		return AcLiveSchedule::select([
				'ac_live_schedules.*',
				'ac_accounts.user_id',
				DB::raw('CONCAT(users.first_name, " ", users.last_name) AS contributor')
			])
			->leftJoin('ac_accounts', function($q) {
				$q->on('ac_live_schedules.account_id', '=', 'ac_accounts.id');
			})
			->leftJoin('users', function($q) {
				$q->on('ac_accounts.user_id', '=', 'users.id');
			})                
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
				->orderBy(1)
				->parameters([
					"scrollY" => "600px",
					"scrollCollapse" => true,
					"paging" => false
				])
				->buttons(
					Button::make('create'),
					Button::make('export'),
					Button::make('print'),
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
			Column::make('title')->name('title'),
			Column::make('slug')->name('slug'),
			Column::make('contributor')->name('users.first_name'),
			//Column::make('excerpt')->name('excerpt'),
			Column::make('eventDatetime')->name('eventDatetime')->title('Datetime (' . config('app.timezone', 'UTC') . ')'),
			Column::make('eventDuration')->name('eventDuration')->title('Duration (Min.)'),
			Column::make('created_at')->name('created_at'),
			Column::make('updated_at')->name('updated_at'),
			Column::computed('action')->exportable(false)->printable(false)->width(120)->addClass('text-center'),
		];
	}

	/**
	 * Get filename for export.
	 *
	 * @return string
	 */
	protected function filename() {
		return 'live_schedules_' . date('YmdHis');
	}

}
