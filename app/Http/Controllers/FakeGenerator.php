<?php

namespace App\Http\Controllers;

use App\helpers\Helpers;
use App\Services\FileService\CsvFileService;
use App\Services\FileService\FileService;
use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;

use App\Models\Type;
use Illuminate\Support\Facades\DB;

class FakeGenerator extends Controller
{
    private Helpers $helper;
    private CsvFileService $fileServiceCsv;
    public function __construct(Helpers $helper, CsvFileService $csvFileService)
    {
        $this->helper = $helper;
        $this->fileServiceCsv = $csvFileService;
    }
    public function generate_preview(Request $request): JsonResponse
    {
        $rows = $request->input('rows');
        $columns_attributes = $request->input('columns_attributes');

        $dataValues = $this->get_data_values($columns_attributes, $rows);

        return response()->json(["data" => $dataValues]);
    }

    public function generate_download(Request $request): \Symfony\Component\HttpFoundation\BinaryFileResponse {
        $rows = $request->input('rows');
        $columns_attributes = $request->input('columns_attributes');
        $separator = $request->input('separator');

        $dataValues = $this->get_data_values($columns_attributes, $rows);

        $this->fileServiceCsv->open_file('test.csv');
        $headers_csv = array_keys($dataValues);;

        //fputcsv($fp, $headers_csv, $separator);
        $this->fileServiceCsv->generate_file_content($headers_csv, $separator);
        $row_csv = [];

        for ($positonE = 0; $positonE < $rows; $positonE++) {
            $row_csv[$positonE] = [];
            foreach($headers_csv as $header) {
                $row_csv[$positonE][] = $dataValues[$header][$positonE];
            }
        }
        foreach ($row_csv as $campos) {
            // fputcsv($fp, $campos, $separator);
            $this->fileServiceCsv->generate_file_content($campos, $separator);
        }

        //fclose($fp);
        $this->fileServiceCsv->close_file();
        return response()->download('test.csv');
    }

    private function get_data_values($columns_attributes, int $rows ): array {
        $dataValues = [];

        foreach ($columns_attributes as $attribute) {
            // get type
            $element = DB::table('types')->where('id', $attribute['type_data'])->get()->first();
            $columnData = [];
            for ($ite = 0; $ite < $rows; $ite++) {
                $fakeValue = $this->helper->get_function_fake_generator($element->name);
                $columnData[] = $fakeValue;
            }

            $dataValues[$attribute['field_name']] = $columnData;
        }

        return $dataValues;
    }
}
