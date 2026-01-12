<?php

namespace App\Imports;

use App\Models\BasicUdid;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\DB;

class BasicUdiImport implements ToModel,WithHeadingRow,WithBatchInserts,WithChunkReading,WithValidation,SkipsOnFailure
{
    use Importable, SkipsFailures;

    private $_rows = 0;
    private $_totalRows = 0;
    private $_skipMsg = "";
    protected $basicudi;

    /**
     * Method prepareForValidation
     *
     * @param $data  $data [explicite description]
     * @param $index $index [explicite description]
     *
     * @return void
     */
    public function prepareForValidation($data, $index)
    {
        ++$this->_totalRows;
        $this->basicudi[$index] = $data;
        return $data;
    }

    /**
     * Method model
     *
     * @param array $row [explicite description]
     *
     * @return void
     */
    public function model(array $row)
    {
        $udiData = [
            'basic_udid_id' => $row['basic_udi_di_eudamed_di'],
            'added_by' => auth()->user()->id,
        ];

        BasicUdid::firstOrCreate(['name' => $row['basic_udi_di_eudamed_di']], $udiData);
    }

    public $requiredHeading = [
        "basic_udi_di_eudamed_di",
    ];

    /**
     * Method rules
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            '*.basic_udi_di_eudamed_di' => ['required', 'unique:basic_udids,name'],
        ];
    }

    /**
     * Method customValidationMessages
     *
     * @return void
     */
    public function customValidationMessages()
    {
        return [
            '*.basic_udi_di_eudamed_di.required' => 'This field is required.',
            '*.basic_udi_di_eudamed_di.unique' => 'This field must unique or already exists.',
        ];
    }

    /**
     * Method batchSize
     *
     * @return int
     */
    public function batchSize(): int
    {
        return 1000;
    }

    /**
     * Method chunkSize
     *
     * @return int
     */
    public function chunkSize(): int
    {
        return 1000;
    }
}
