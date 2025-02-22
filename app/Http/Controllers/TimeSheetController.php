<?php

namespace App\Http\Controllers;

use App\DataTables\TimeSheetDataTable;
use Illuminate\Http\Request;

class TimeSheetController extends Controller
{
    public function index(TimeSheetDataTable $dataTable)
    {
        return $dataTable->render('time_sheet.time_sheet');
    }
}
