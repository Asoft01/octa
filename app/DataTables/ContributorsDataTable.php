<?php

namespace App\DataTables;

use App\Models\AcAccount;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use App\Models\Auth\Role;

class ContributorsDataTable extends DataTable
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
            ->addColumn('action', '<a href="'.route('admin.library.contributors.edit').'/{{$id}}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> Edit</a>')
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
        ->where('role_id', 4)
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
			Column::make('first_name')->title('First Name'),
            Column::make('last_name')->title('Last Name'),
			Column::make('slug')->title('Slug'),
            Column::make('photo')->title('Photo'),
            Column::make('icon')->title('Icon'),
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
        return 'contributors_' . date('YmdHis');
    }
}

