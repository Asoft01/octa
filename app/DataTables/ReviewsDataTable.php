<?php

namespace App\DataTables;

use App\Models\AcReview;
use App\Models\AcContent;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\DB;

class ReviewsDataTable extends DataTable
{
	
	const VISIBILITY_COLUMN_RAW_SQL = "IF(ISNULL(ac_contents.isPublic), 'NULL', IF(ac_contents.isPublic > 0, 'Public', 'Private'))";

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', '<a href="https://agora.community/preview/{{ $slug }}" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a> <a href="'.route('admin.library.reviews.edit').'/{{$id}}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> Edit</a>')
            ->editColumn('thumb', '<img style="max-width: 120px;" src="{{ config("ac.SIH") }}{{ config("ac.THUMB_RES") }}{{ $poster }}">')
            ->editColumn('syncsketch', '<a href="{{ $syncsketch }}">Syncsketch</a>')
            ->editColumn('slug', '<a href="https://agora.community/content/{{ $slug }}">{{ $slug }}</a>')
			->filterColumn('visibility', function($query, $keyword) {
				$query->whereRaw(self::VISIBILITY_COLUMN_RAW_SQL . ' like ?', ["%{$keyword}%"]);
			})
            ->rawColumns(['slug', 'syncsketch','thumb', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Order $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        //return AcReview::where('mentor_id', 16)->newQuery();
        //return $model->newQuery();
        /*return AcContent::
        where('contentable_type', 'MorphReview') 
        ->where('display_start', '<=', now())
        ->where('isPublic', 1)
        ->orderBy('display_start', 'desc')
        ->take(10)
        ->joinRelationship('contentable')
        ->newQuery();*/
        return AcReview::select(
            [
                'ac_contents.title',
                'ac_contents.slug',
                'ac_contents.cotd_start',
				DB::raw(self::VISIBILITY_COLUMN_RAW_SQL . ' as visibility'),
                'ac_contents.display_start',
                'ac_reviews.*',
                'ac_accounts.user_id'
            ])->leftJoin('ac_contents', function($q) {
                $q->on('ac_contents.contentable_id', '=', 'ac_reviews.id');
                $q->where('ac_contents.contentable_type', '=', 'MorphReview');
            })
            ->leftJoin('ac_accounts', function($q) {
                    $q->on('ac_reviews.mentor_id', '=', 'ac_accounts.id');
                })
                ->leftJoin('users', function($q) {
                    $q->on('ac_accounts.user_id', '=', 'users.id');
                })                
            ->newQuery();
        //return $m->newQuery();

    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
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
                    )
					->rowCallback("function(row, data, dataIndex) { if (data.visibility !== 'Public') { $(row).addClass('table-primary'); } }");
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('id')->hidden(),
            Column::make('thumb')->title('Thumbnail'),
            Column::make('title')->name('ac_contents.title'),
            Column::make('mentor.user.full_name')->name('users.first_name')->title('Reviewer'),
            Column::make('syncsketch'),
            Column::make('slug')->name('ac_contents.slug'),
			Column::make('visibility')->name('visibility'),
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
    protected function filename()
    {
        return 'Orders_' . date('YmdHis');
    }
}
