<?php

namespace App\Repositories;

use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class SettingRepository extends BaseRepository
{
    protected $model;
    
    /**
     * Method __construct
     *
     * @param Setting $model [explicite description]
     *
     * @return void
     */
    public function __construct(Setting $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * Method GetImage
     *
     * @return void
     */
    public function get()
    {
        return $this->model->where('type', 'leaderboard_image')->first();
    }
    
    /**
     * Method create
     *
     * @param array $data [explicite description]
     *
     * @return void
     */
    public function create(array $data)
    {
        $leaderBoardImage = $this->model->first();
        if (! empty($data['value'])) {
            $data['value'] = uploadFile(
                $data['value'],
                'leaderboard_image'
            );
            if (! empty($leaderBoardImage)) {
                deleteFile($leaderBoardImage->value);
            }

        } else {
            unset($data['value']);
        }
        if ($leaderBoardImage) {
            return $this->model->where('id', $data['id'])->update($data);
        }

        return $this->model->create($data);

    }
    
    /**
     * Method delete
     *
     * @param $id $id [explicite description]
     *
     * @return void
     */
    public function delete($id)
    {
        $check = $this->model->find($id);
        if (! $check) {
            return abort(404);
        }

        return $this->model->find($id)->delete();
    }
    
    /**
     * Method getDetail
     *
     * @param int $id [explicite description]
     *
     * @return void
     */
    public function getDetail(int $id)
    {
        return $this->model->find($id);
    }
    
    /**
     * Method updateDetail
     *
     * @param array $data [explicite description]
     *
     * @return bool
     */
    public function updateDetail(array $data): bool
    {

        $appName = $this->model->where('setting_key', 'app.name')->first();
        if ($data['app_name'] != $appName->setting_value) {
            $updatedData['setting_value'] = $data['app_name'];
            $this->update($updatedData, $appName->id);
        }
        $appGoogleAnalytics = $this->model->where('setting_key', 'app.google_analytics')->first();
        if ($data['google_analytics'] != $appGoogleAnalytics->setting_value) {
            $updatedData['setting_value'] = $data['google_analytics'];
            $this->update($updatedData, $appGoogleAnalytics->id);
        }

        if (! empty($data['app_logo']) && is_uploaded_file($data['app_logo'])) {
            $logo = $this->model->where('setting_key', 'app.logo')->first();
            $updatedData['setting_value'] = uploadFile(
                $data['app_logo'],
                config('constants.image.logo.path')
            );
            if (! empty($logo->setting_value)) {
                deleteFile($logo->setting_value);
            }
            $this->update($updatedData, $logo->id);
        }

        return true;
    }
    
    /**
     * Method runCommand
     *
     * @param $slug $slug [explicite description]
     *
     * @return void
     */
    public function runCommand($slug)
    {
        Log::channel('debug')->debug('slug', ['slug' => $slug]);
        switch ($slug) {

        case 'opcl':
            Artisan::call('optimize:clear');
            Log::channel('debug')->debug('slug optimize:clear');
            break;

        case 'coca':
            Artisan::call('config:cache');
            Log::channel('debug')->debug('slug config:cache');
            break;

        case 'clear':
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('config:cache');
            Log::channel('debug')->debug('slug clear config cache view');
            break;

        case 'rm':
            Artisan::call('migrate --force');
            Log::channel('debug')->debug('slug run migrate view');
            break;

        case 'rmf':
            Artisan::call('migrate:fresh --seed --force');
            Log::channel('debug')->debug('slug run migrate fresh --seed ');
            break;

        case 'up':
            Artisan::call('up');
            Log::channel('debug')->debug('slug run maintenance mode up');
            break;

        case 'down':
            Artisan::call('down --redirect=/');
            Log::channel('debug')->debug('slug run maintenance mode down');
            break;

        }

        return true;
    }

    /**
     * Get all record and return app_key => value
     *
     * @return array
     */
    public function getAll()
    {
        $settings = $this->model->get();
        $returnSetting = [];
        foreach ($settings as $key => $value) {
            $returnSetting[$value->setting_key] = $value->setting_value;
        }

        return $returnSetting;
    }
}
