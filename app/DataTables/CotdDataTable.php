<?php

namespace App\DataTables;

use App\Models\AcContent;
use App\Models\AcVideo;
use App\Models\AcReview;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CotdDataTable extends DataTable
{
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
            ->editColumn('slug', '<a href="https://agora.community/content/{{ $slug }}">{{ $slug }}</a>')
            ->rawColumns(['slug']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Order $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return AcContent::select('*')->newQuery();
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
                    ->orderBy(5, 'desc')
                    ->parameters([
                        "scrollY" =>        "600px",
                        "scrollCollapse" => true,
                        "paging" =>         false
                    ])
                    ->buttons(
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
    protected function getColumns()
    {
        return [
            Column::make('id')->hidden(),
            Column::make('title')->name('ac_contents.title'),
            Column::make('contentable_type')->name('ac_contents.contentable_type'),
            Column::make('slug')->name('ac_contents.slug'),
            Column::make('display_start')->name('ac_contents.display_start'),
            Column::make('cotd_start')->name('ac_contents.cotd_start'),
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

