<?php

namespace App\Http\Controllers;

use App\DataTables\SettingDataTable;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(SettingDataTable $dataTable){
        return $dataTable->render('pages.setting');

    }
}
