<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use League\Csv\Statement;

class WebImportController extends Controller
{
    public function indexAction()
    {
        $database_tables = $this->getTables();
        return view('webImport.pages.index', compact('database_tables'));
    }

    public function uploadAction(Request $request)
    {
        $path        = $request->file('inputFile')->store('webimport/file_buffers');
        $pathEncrypt = encrypt($path);
        $inputs      = $request->all();

        return redirect("webimport/fields/" . $pathEncrypt . "/" . $inputs["table_name"]);
    }

    private function getColumns($table_name)
    {
        $columns  = DB::select('show columns from ' . $table_name);
        $_columns = [];

        foreach ($columns as $column) {
            $_columns[] = $column;
        }
        return $_columns;
    }

    private function getTables()
    {

        $tables  = DB::select('SHOW TABLES');
        $tables_ = [];

        foreach ($tables as $table) {
            $tables_[] = $table->Tables_in_my_database;
        }
        return $tables_;
    }

    private function readCSVFile($filePath)
    {
        $stream = fopen($filePath, 'r');
        $csv    = Reader::createFromStream($stream);

        $csv->setDelimiter(',');
        $csv->setHeaderOffset(1);
        return $csv;
    }

    private function getCSVColumns($filePath)
    {
        $fields  = [];
        $csv     = $this->readCSVFile($filePath);
        $stmt    = (new Statement())->limit(1);
        $records = $stmt->process($csv);

        foreach ($records->getRecords() as $record) {
            $fields = $record;
        }
        return $fields;
    }

    public function matchingFieldAction(Request $request, $encrypt_paht, $table)
    {
        $path     = decrypt($encrypt_paht);
        $filePath = '../storage/app/' . $path;
        $columns  = $this->getColumns($table);

        session(['webimport_file_path' => $filePath]);
        session(['webimport_table'     => $table]);

        $csv     = $this->readCSVFile($filePath);
        $stmt    = (new Statement())->limit(1);
        $records = $stmt->process($csv);
        $fields  = [];

        foreach ($records->getRecords() as $record) {
            $fields = $record;
        }

        return view('webImport.pages.mapping_fields', compact('fields', 'columns'));
    }


    public function importAction(Request $request)
    {
    $input               = $request->all();
    $webimport_file_path = session('webimport_file_path', null);
    $webimport_table     = session('webimport_table', null);
    $csvColumns          = $this->getCSVColumns($webimport_file_path);
    $csvRows             = $this->readCSVFile($webimport_file_path);
    $count               = 0;
    $done_count          = 0;

        foreach ($csvRows as $csvRow){
            $count++;
            $value = [];
            foreach ($input["input"] as $databaseField => $csvField ) {
                if ($csvField != 'null') {
                    $value[$databaseField] = $csvRow[array_search($csvField, $csvColumns)];
                }
            }

            try{
                DB::table($webimport_table)->insert([$value]);
                $done_count++;
            }catch (Exception $exception){
                print_r($value);
                print_r("Can't insert because some problem.!");
                print_r("<hr>");
            }

        }

         print_r("All(".$count."),Success(".$done_count.")");


    }
}
