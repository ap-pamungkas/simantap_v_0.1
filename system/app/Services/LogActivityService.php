<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Facades\Activity;
use Illuminate\Support\Facades\Request; // Use Facade for Request

class LogActivityService
{
    /**
     * Log activity for a given model record.
     *
     * @param Model $model
     * @param string $action
     * @param array $additionalProperties
     * @param string|null $displayAttributeName The name of the attribute to display in the log, e.g., 'nama_jabatan' or 'name'.
     * @return void
     */
    public function logActivity(Model $model, string $action, array $additionalProperties = [], ?string $displayAttributeName = null): void
    {
        // Define default properties including IP address and User Agent
        $properties = array_merge([
            'ip_address' => Request::ip(), // Use Request Facade
            'user_agent' => Request::header('User-Agent'),
            // 'id '.$model =>  // Use Request Facade
        ], $additionalProperties);

        // Get the model's class name without namespace
        $modelName = class_basename($model);

        // Construct the log description dynamically based on the model's attributes
        // Use the provided displayAttributeName, or fallback to 'nama_jabatan', 'name', or the model's key.
        $displayAttribute = $model->getAttribute($displayAttributeName)
                            ?? $model->getAttribute('name')
                            ?? $model->getKey();

        Activity::performedOn($model)
            ->withProperties($properties)
            ->log("{$modelName} \"{$displayAttribute}\" telah berhasil di{$action}.");
    }
}
