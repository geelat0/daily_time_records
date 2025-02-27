<?php

namespace App\DataTables;

use App\Models\TimeEntries;
use App\Models\TimeSheet;
use Carbon\Carbon;
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
            ->editColumn('id', function ($row) {
                return $row->id ?? '';
            })
            ->addColumn('Day', function ($row) {
                if ($row->created_at) {
                    return $row->created_at->format('D, M d');
                } else {
                    return Carbon::parse($row->temp_date)->format('D, M d');
                }
                
            })
            ->editColumn('schedule', function ($row) {
                $amTimeIn = $row->shiftSchedule->shift->am_time_in ?? '';
                $amTimeOut = $row->shiftSchedule->shift->am_time_out ?? '';
                $pmTimeIn = $row->shiftSchedule->shift->pm_time_in ?? '';
                $pmTimeOut = $row->shiftSchedule->shift->pm_time_out ?? '';
                return $amTimeIn. ' - ' . $amTimeOut. ' ' . $pmTimeIn. ' - ' . $pmTimeOut;
            })
            ->editColumn('am_time_in', function ($row) {
                return $row->am_time_in ? Carbon::parse($row->am_time_in)->format('H:i') : '';
            })
            ->editColumn('pm_time_out', function ($row) {
                return $row->pm_time_out ? Carbon::parse($row->pm_time_out)->format('H:i') : '';
            })
            ->editColumn('am_time_out', function ($row) {
                return $row->am_time_out ? Carbon::parse($row->am_time_out)->format('H:i') : '';
            })
            ->editColumn('pm_time_in', function ($row) {
                return $row->pm_time_in ? Carbon::parse($row->pm_time_in)->format('H:i') : '';
            })
            ->editColumn('rendered_hours', function ($row) {
                return $row->rendered_hours ?  Carbon::parse($row->rendered_hours)->format('H:i') : '00:00';
            })
            ->editColumn('excess_minutes', function ($row) {
                return $row->excess_minutes ?  Carbon::parse($row->excess_minutes)->format('H:i') : '00:00';
            })
            ->editColumn('late_hours', function ($row) {
                return $row->late_hours ?  Carbon::parse($row->late_hours)->format('H:i') : '00:00';
            })
            ->editColumn('remarks', function ($row) {
                $day = $row->created_at ? $row->created_at->format('D') : Carbon::parse($row->temp_date)->format('D');
                if ($day == 'Sat' || $day == 'Sun') {
                    return '<span style="color: #F05655; font-style:italic">Rest Day</span>';
                }
                return $row->remarks ?? '';
            })
            ->addColumn('Edit', function ($row) {
                return '<a href="/return/${row.id}" class="text-decoration-underline fst-italic custom-text">Edit</a>';

            })
            ->addColumn('attachment', function ($row) {
                return '<a href="data:application/octet-stream;base64,' . $row->file_path . '" class="text-decoration-underline fst-italic" download="' . $row->file_name . '">' . $row->file_name . '</a>';})

            ->rawColumns(['Day', 'Edit', 'remarks', 'attachment']);

    }

    /**
     * Get the query source of dataTable.
     */
    public function query(TimeEntries $model): QueryBuilder
    {
        $startDate = request('start_date');
        $endDate = request('end_date');

        if (!$startDate || !$endDate) {
            $startDate = now()->startOfMonth()->format('Y-m-d');
            $endDate = now()->endOfMonth()->format('Y-m-d');
        }

        $dates = [];
        $currentDate = Carbon::parse($startDate);

        while ($currentDate->format('Y-m-d') <= $endDate) {
            $dates[] = [
                'day' => $currentDate->day,
                'date' => $currentDate->format('Y-m-d'),
            ];
            $currentDate->addDay();
        }

        $tempDatesQuery = implode(' UNION ALL SELECT ', array_map(function($date) {
            return "'{$date['day']}' as day, '{$date['date']}' as date";
        }, $dates));

        return $model->newQuery()
            ->select('time_entries.*', 'temp_dates.day as temp_day', 'temp_dates.date as temp_date', 'approved_attendance.file_name', 'approved_attendance.file_path')
            ->from(DB::raw("(SELECT $tempDatesQuery) as temp_dates"))
            ->leftJoin('time_entries', DB::raw('DATE(time_entries.created_at)'), '=', 'temp_dates.date')
            ->leftJoin('approved_attendance', function ($join) {
                $join->on(DB::raw('DATE_FORMAT(temp_dates.date, "%Y-%m-%d")'), '>=', 'approved_attendance.start_date')
                     ->on(DB::raw('DATE_FORMAT(temp_dates.date, "%Y-%m-%d")'), '<=', 'approved_attendance.end_date');
            })
            ->whereBetween('temp_dates.date', [$startDate, $endDate]);

    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('timesheetTable')
                    ->columns($this->getColumns())
                    ->ajax(
                        ['data' => 'function(d) {
                            
                            d.start_date = $("#startDate").val();
                            d.end_date = $("#endDate").val();
                        }'
                    ])
                    
                    ->dom('rtip')
                    ->ordering(false) // Disable sorting
                    ->pageLength(10); // Show 10 rows by default
                  
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->hidden(),
            Column::make('Day'),
            // Column::make('schedule'),
            Column::make('am_time_in'),
            Column::make('am_time_out'),
            Column::make('pm_time_in'),
            Column::make('pm_time_out'),
            Column::make('rendered_hours')->title('Rendered'),
            Column::make('excess_minutes')->title('Excess'),
            Column::make('late_hours')->title('Late'),
            Column::make('remarks'),
            Column::make('attachment'),
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
