<?php

namespace App\DataTables;

use App\Models\AcAsset;
use App\Models\AcContent;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AssetsDataTable extends DataTable
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
            ->addColumn('action', '<a href="'.route('admin.library.assets.edit').'/{{$id}}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> Edit</a>')
            ->editColumn('thumb', '<img style="max-width: 120px;" src="{{ config("ac.SIH") }}{{ config("ac.THUMB_RES") }}{{ $poster }}">')
            ->rawColumns(['thumb', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Order $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return AcAsset::select(['ac_contents.title', 'ac_contents.display_start', 'ac_contents.cotd_start', 'ac_assets.*'])->leftJoin('ac_contents', function($q) {
            $q->on('ac_contents.contentable_id', '=', 'ac_assets.id');
            $q->where('ac_contents.contentable_type', '=', 'MorphAsset');
        })->newQuery();
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
                    ->orderBy(3)
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
    protected function getColumns()
    {
        return [
            Column::make('id')->hidden(),
            Column::make('thumb')->title('Thumbnail'),
            Column::make('title')->name('ac_contents.title'),
            Column::make('display_start')->name('ac_contents.display_start'),
            Column::make('cotd_start')->name('ac_contents.cotd_start'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
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

