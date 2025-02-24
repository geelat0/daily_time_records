<?php

namespace App\Http\Controllers;

use App\DataTables\TimeSheetDataTable;
use App\Models\TimeEntries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB ;

class TimeSheetController extends Controller
{
    public function index(TimeSheetDataTable $dataTable)
    {
        return $dataTable->render('time_sheet.time_sheet');
    }
}
