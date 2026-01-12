<?php

namespace App\Imports;

use App\Models\BasicUdid;
use App\Models\User;
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

class AdminBasicUdiImport implements ToModel,WithHeadingRow,WithBatchInserts,WithChunkReading,WithValidation,SkipsOnFailure
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
        $actor = $this->_getClient($row['actor_idsrn']);

        if (!$actor) {
            return $actor;
        }

        $udiData = [
            'basic_udid_id' => $row['basic_udi_di_eudamed_di'],
            'added_by' => $actor,
        ];

        BasicUdid::firstOrCreate(['name' => $row['basic_udi_di_eudamed_di']], $udiData);
    }

    /**
     * Method _getClient
     *
     * @param string $srnNumber [explicite description]
     *
     * @return int
     */
    private function _getClient(string $srnNumber):?int
    {
        $client = User::where(['actor_id' => $srnNumber])->first();
        return ($client) ? $client->id : null;
    }

    public $requiredHeading = [
        "basic_udi_di_eudamed_di",
        "actor_idsrn",
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
            '*.actor_idsrn' => ['required', 'exists:users,actor_id'],
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
            '*.actor_idsrn.required' => 'This field is required.',
            '*.actor_idsrn.exists' => "This actor id not exist of any client, please add/register it.",
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
