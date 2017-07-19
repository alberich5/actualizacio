<?php

namespace Omar\Http\Controllers;

use Illuminate\Http\Request;

use Omar\Http\Requests;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    
	public function excel(Request $request)
    {
    	Excel::create('Laravel Excel', function($excel) {

    $excel->sheet('Excel sheet', function($sheet) {

        $sheet->setOrientation('landscape');

    });

	})->export('xls');

    }


}
