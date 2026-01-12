<?php

namespace App\Imports;

use App\Models\BasicUdid;
use App\Models\Product;
use App\Models\ProductClass;
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

class ProductImport implements ToModel,WithHeadingRow,WithBatchInserts,WithChunkReading,WithValidation,SkipsOnFailure
{
    use Importable, SkipsFailures;

    private $_rows = 0;
    private $_totalRows = 0;
    private $_skipMsg = "";
    protected $product;

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
        $this->product[$index] = $data;
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

        $productData = [
            'product_name' => $row['trade_name'],
            'udi_number' => $row['udi_di_eudamed_id'],
            'basic_udid_id' => $this->_getBasicUdId($row['basic_udi_di_eudamed_di'], $actor),
            'class_id' => $this->_getClass($row['risk_class']),
            'client_id' => $this->_getClient($row['actor_idsrn']),
            'verification_by' => (auth()->user()->user_type == User::TYPE_CLIENT) ? Product::VERIFICATION_EUDAMED : Product::VERIFICATION_UDI_EU,
            'is_import' => 1,
            'added_by' => auth()->user()->id,
        ];

        Product::firstOrCreate(['udi_number' => $row['udi_di_eudamed_id']], $productData);
    }

    /**
     * Method _getBasicUdId
     *
     * @param string $number   [explicite description]
     * @param int    $clientId [explicite description]
     *
     * @return int
     */
    private function _getBasicUdId(string $number, int $clientId):int
    {
        $basicUdIdNumber = BasicUdid::firstOrCreate(
            [
                'name' => $number
            ],
            [
                'name' => $number,
                'added_by' => $clientId
            ]
        );

        return $basicUdIdNumber->id;
    }

    /**
     * Method _getClass
     *
     * @param string $className [explicite description]
     *
     * @return int
     */
    private function _getClass(string $className):int
    {
        $classNames = [
            'Class I' => 'Class 1',
            'Class II' => 'Class 2',
            'Class IIa' => 'Class 2',
            'Class III' => 'Class 3'
        ];
        $resolvedClassName = $classNames[$className] ?? $className;
        $productClass = ProductClass::firstOrCreate(
            [
                'name' => $resolvedClassName
            ],
            [
                'name' => $resolvedClassName
            ]
        );

        return $productClass->id;
    }

    /**
     * Method _getClient
     *
     * @param string $srnNumber [explicite description]
     *
     * @return int|null
     */
    private function _getClient(string $srnNumber):?int
    {
        $client = User::where(['actor_id' => $srnNumber])->first();

        return ($client) ? $client->id : null;
    }

    public $requiredHeading = [
        "udi_di_eudamed_id",
        "trade_name",
        "basic_udi_di_eudamed_di",
        "risk_class",
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
            '*.udi_di_eudamed_id' => ['required', 'unique:products,udi_number'],
            '*.trade_name' => ['required'],
            '*.basic_udi_di_eudamed_di' => ['required',

            function ($attribute, $value, $fail) {
                if (!empty($this->product[$attribute[0]]['actor_idsrn']) && !empty($this->product[$attribute[0]]['basic_udi_di_eudamed_di'])) {
                    $actorId = $this->product[$attribute[0]]['actor_idsrn'];
                    $basicUdiId = $this->product[$attribute[0]]['basic_udi_di_eudamed_di'];
                    $isExist = $this->checkBasicUdi($actorId, $basicUdiId);

                    if ($isExist && !empty($isExist)) {
                        $fail("This Basic UDI not from this actor's id client.");
                    }
                }
            }

            ],
            '*.risk_class' => ['required'],
            '*.actor_idsrn' => ['required', 'exists:users,actor_id'],
        ];
    }

    /**
     * Method checkBasicUdi
     *
     * @param $actorId    $actorId [explicite description]
     * @param $basicUdiId $basicUdiId [explicite description]
     *
     * @return void
     */
    public function checkBasicUdi($actorId, $basicUdiId) // check basic udi for this client or not of actor ids of current row client.
    {
        if ($actorId != null) {
            $user = User::where("actor_id", $actorId)->first();
            if ($user) {
                return BasicUdid::where("name", $basicUdiId)->where("added_by", "<>", $user->id)->first();
            }
        }
    }

    /**
     * Method customValidationMessages
     *
     * @return void
     */
    public function customValidationMessages()
    {
        return [
            '*.udi_di_eudamed_id.required' => 'This field is required.',
            '*.udi_di_eudamed_id.unique' => 'This field must unique or already exists.',
            '*.trade_name.required' => 'This field is required.',
            '*.basic_udi_di_eudamed_di.required' => 'This field is required.',
            '*.risk_class.required' => 'This field is required.',
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
