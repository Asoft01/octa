<?php

namespace App\DataTables;

use App\Models\AcAccount;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use App\Models\Auth\Role;

class ExpertsDataTable extends DataTable
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
            ->addColumn('action', '<a href="'.route('admin.library.experts.edit').'/{{$id}}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> Edit</a>')
            ->editColumn('photo', '<img style="max-width: 120px;" src="{{ asset_cdn($photo) }}">')
            ->editColumn('icon', '<img style="max-width: 48px;" src="{{ asset_cdn($icon) }}">')
            ->rawColumns(['photo', 'icon', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Order $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return AcAccount::select(
            [
                'ac_accounts.id',
				'ac_accounts.slug',
                'ac_accounts.photo',
                'ac_accounts.icon',
                'ac_accounts.hoursWeek',
                'ac_accounts.delay',
                'ac_accounts.bookeduntil',
                'users.first_name',
                'users.last_name',
                'role_id'
        ])
        ->leftJoin('model_has_roles', function($q) {
            $q->on('model_has_roles.model_id', '=', 'ac_accounts.user_id');
        })
        ->leftJoin('users', function($q) {
            $q->on('ac_accounts.user_id', '=', 'users.id');
        })
        ->where('model_has_roles.role_id', 3)
        ->newQuery();
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
                    ->orderBy(2, 'acs')
                    ->parameters([
                        "scrollCollapse" => false,
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
            Column::make('photo')->title('Photo'),
			Column::make('first_name')->title('First Name'),
            Column::make('last_name')->title('Last Name'),
			Column::make('slug')->title('Slug'),
            Column::make('hoursWeek')->title('hours per week'),
            Column::make('delay')->title('Delay in order (days)'),
            Column::make('bookeduntil')->title('Unavailable until'),
            //Column::make('icon')->title('Icon'),
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
        return 'experts_' . date('YmdHis');
    }
}

