<?php

namespace App\DataTables;

use App\Models\TimeEntries;
use App\Models\TimeSheet;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TimeSheetDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->setRowId('id')
            ->addColumn('Day', function ($row) {
                $dateParts = explode(' ', $row->date);
                return '<div style="">
                            <div style="font-weight: bold;">' . $dateParts[0] . '</div>
                            <div>' . $dateParts[1] . ' ' . $dateParts[2] . '</div>
                        </div>';
            })
            ->editColumn('time_in', function ($row) {
                return $row->time_in ?? '';
            })
            ->editColumn('time_out', function ($row) {
                return $row->time_out ?? '';
            })
            ->editColumn('break_out', function ($row) {
                return $row->break_out ?? '';
            })
            ->editColumn('break_in', function ($row) {
                return $row->break_in ?? '';
            })
            ->editColumn('rendered_hours', function ($row) {
                return $row->rendered_hours ?? '0.0.0';
            })
            ->editColumn('excess_minutes', function ($row) {
                return $row->excess_minutes ?? '0.0.0';
            })
            ->editColumn('late', function ($row) {
                return $row->late ?? '0.0.0';
            })
            ->editColumn('status', function ($row) {
                return $row->status ?? '';
            })
            ->addColumn('Edit', function ($row) {
                return '<button class="btn btn-custom">Edit</button>';
            })
            ->rawColumns(['Day', 'Edit']);


        
            // ->addColumn('action', 'timesheet.action');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(TimeEntries $model): QueryBuilder
    {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
        $dates = [];
        
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = now()->startOfMonth()->addDays($day - 1);
            $dates[] = [
                'id' => $day,
                'date' => $date->format('D, M d'),
            ];
        }
        
        return $model->newQuery()
            ->select('*')
            ->from(DB::raw('(SELECT ' . implode(' UNION ALL SELECT ', array_map(function($date) {
                return "'{$date['id']}' as id, '{$date['date']}' as date";
            }, $dates)) . ') as temp_dates'));
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('timesheet-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('rtip')
                    ->ordering(false) // Disable sorting
                    ->pageLength(31) // Show 31 rows (for months with 31 days)
                    ->lengthMenu([31]); // Only allow 31 rows option
                  
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->hidden(),
            Column::make('Day'),
            Column::make('time_in'),
            Column::make('break_out'),
            Column::make('break_in'),
            Column::make('time_out'),
            Column::make('rendered_hours'),
            Column::make('excess_minutes'),
            Column::make('late'),
            Column::make('status'),
            Column::make('Edit'),
            // Column::make('Action'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'TimeSheet_' . date('YmdHis');
    }
}
