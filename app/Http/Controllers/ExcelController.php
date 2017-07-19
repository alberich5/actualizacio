<?php

namespace Omar\Http\Controllers;

use Illuminate\Http\Request;

use Omar\Http\Requests;

class ExcelController extends Controller
{
    
	public function index(Request $request)
    {
        Excel::create('Mi primer archivo excel desde Laravel', function($excel)
  		{
   $excel->sheet('Sheetname', function($sheet)
   {
    $sheet->mergeCells('A1:C1');
 
    $sheet->setBorder('A1:F1', 'thin');
 
    $sheet->cells('A1:F1', function($cells)
    {
     $cells->setBackground('#000000');
     $cells->setFontColor('#FFFFFF');
     $cells->setAlignment('center');
     $cells->setValignment('center');
    });
 
    $sheet->setWidth(array
     (
      'D' => '50'
     )
    );
 
    $sheet->setHeight(array
     (
      '1' => '50'
     )
    );
 
    $data=[];
 
    array_push($data, array('Kevin', '', '', 'Arnold', 'Arias', 'Figueroa'));
 
    $sheet->fromArray($data, null, 'A1', false, false);
   });
  })-><span id="IL_AD2" class="IL_AD">download</span>('xlsx');
    }


}
