<?php

namespace App\DataTables;

use App\Models\AcPlaylist;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\DB;

class PlaylistsDataTable extends DataTable {
	/**
	 * Build DataTable class.
	 *
	 * @param mixed $query Results from query() method.
	 * @return \Yajra\DataTables\DataTableAbstract
	 */
	public function dataTable($query) {
		return datatables()
			->eloquent($query)
			->addColumn('action',
				'<div style="white-space: pre;">' .
					'<a href="{{ route("admin.library.playlists.dump", $id) }}" class="btn btn-sm btn-secondary" title="Dump"><i class="fas fa-code"></i></a>' .
					'<a href="{{ route("admin.library.playlists.edit", $id) }}" class="btn btn-sm btn-primary ml-1" title="Edit"><i class="fas fa-edit"></i> Edit</a>' .
				'</div>'
			)
			->editColumn('poster', '@if($poster) <img style="max-width: 120px;" src="{{ config("ac.SIH") . config("ac.THUMB_RES") . $poster }}"> @endif')
			->editColumn('slug', '<a href="{{ route("frontend.content", $slug) }}">{{ $slug }}</a>')
			->editColumn('user', '@if($account_slug) <a href="{{ route("frontend.contributor", $account_slug) }}">{{ $user_full_name }}</a> @else {{ $user_full_name }} @endif')
			->rawColumns(['action', 'poster', 'slug', 'user']);
	}

	/**
	 * Get query source of dataTable.
	 *
	 * @param \App\Order $model
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function query() {
		return AcPlaylist::select([
			'ac_playlists.id',
			'ac_playlists.user_id',
			'ac_playlists.poster',
			'ac_contents.title',
			'ac_contents.slug',
			'ac_contents.cotd_start',
			'ac_contents.display_start',
			'ac_category_contents.ac_category_id',
			'ac_categories.title as category',
			'ac_accounts.slug as account_slug',
			DB::raw('CONCAT(users.first_name, " ", users.last_name) AS user_full_name'),
			DB::raw('(SELECT COUNT(ac_content_playlists.id) FROM ac_content_playlists WHERE ac_content_playlists.playlist_id = ac_playlists.id) AS subitem_count')
		])
        ->leftJoin('ac_contents', function($q) {
            $q->on('ac_contents.contentable_id', '=', 'ac_playlists.id');
            $q->where('ac_contents.contentable_type', '=', 'MorphPlaylist');
        })
        ->leftJoin('ac_category_contents', function($q) {
            $q->on('ac_category_contents.ac_content_id', '=', 'ac_contents.id');
        })
        ->leftJoin('ac_categories', function($q) {
            $q->on('ac_category_contents.ac_category_id', '=', 'ac_categories.id');
        })
		->leftJoin('ac_accounts', function($q) {
			$q->on('ac_accounts.user_id', '=', 'ac_playlists.user_id');
		})
		->leftJoin('users', function($q) {
			$q->on('ac_playlists.user_id', '=', 'users.id');
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
					->orderBy(7)
					->parameters([
                        "scrollY" =>        "600px",
                        "scrollCollapse" => true,
                        "paging" =>         false
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
            Column::make('poster')->title('Poster'),
            Column::make('title')->name('ac_contents.title'),
            Column::make('slug')->name('ac_contents.slug'),
			Column::make('user')->name('user_full_name'),
            Column::make('category')->name('ac_categories.title'),
			Column::make('subitem_count')->name('subitem_count')->title('Items'),
            Column::make('display_start')->name('ac_contents.display_start'),
            Column::make('cotd_start')->name('ac_contents.cotd_start'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(120)
                  ->addClass('text-center'),
        ];
	}

	/**
	 * Get filename for export.
	 *
	 * @return string
	 */
	protected function filename() {
		return 'playlists_' . date('YmdHis');
	}
}

