<?php

require __DIR__ . '/../vendor/autoload.php';

const API_TOKEN = 'your-api-token';

use Hapio\Sdk\ApiClient;
use Hapio\Sdk\Exceptions\ErrorException;

$apiClient = new ApiClient(API_TOKEN);

try {
    $project = $apiClient->projects()->getCurrentProject();

    echo 'Project ID: ' . $project->id . PHP_EOL;
    echo 'Project name: ' . $project->name . PHP_EOL;
    echo 'Enabled: ' . ($project->enabled ? 'Yes' : 'No') . PHP_EOL;
    echo 'Created at: ' . $project->created_at->format('Y-m-d H:i:s P') . PHP_EOL;
    echo 'Updated at: ' . $project->created_at->format('Y-m-d H:i:s P') . PHP_EOL;
} catch (ErrorException $e) {
    echo 'API request failed: ' . $e->getMessage() . ' (' . $e->getCode() . ').' . PHP_EOL;
}
