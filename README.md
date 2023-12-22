# Hapio SDK for PHP

The Hapio SDK for PHP allows you to easily interact with the Hapio API for bookings and scheduling when using the PHP programming language.

## Requirements

- PHP 8.2 or later

## Getting started

The easiest way to install the Hapio SDK is by using [Composer](https://getcomposer.org/) to require the package in your project:

```bash
composer require hapio/hapio-sdk-php
```

To use the SDK in your project you simply instantiate an API client, and then get to work:

```php
// Require the Composer autoloader
require __DIR__ . '/vendor/autoload.php';

use Hapio\Sdk\ApiClient;

// Instantiate a Hapio API client
$apiClient = new ApiClient('your-api-token');

// Get a list of all bookings this week
$response = $apiClient->bookings()->list([
    'from' => new DateTime('monday this week 00:00:00'),
    'to' => new DateTime('friday this week 23:59:59'),
]);

while ($response) {
    foreach ($response->getItems() as $booking) {
        var_dump($booking);
    }

    if ($response->hasMoreItems()) {
        $response = $response->getNextPage();
    } else {
        $response = null;
    }
}
```

## Documentation

### API client

The `Hapio\Sdk\ApiClient` class is a subclass of `GuzzleHttp\Client`. To instantiate it, you simply provide your API token to its constructor:

```php
use Hapio\Sdk\ApiClient;

$apiClient = new ApiClient('your-api-token');
```

### Models

The following models are available in the SDK:

- `Hapio\Sdk\Models\Project`
- `Hapio\Sdk\Models\Location`
- `Hapio\Sdk\Models\Resource`
- `Hapio\Sdk\Models\Service`
- `Hapio\Sdk\Models\ScheduleBlock`
- `Hapio\Sdk\Models\RecurringSchedule`
- `Hapio\Sdk\Models\RecurringScheduleBlock`
- `Hapio\Sdk\Models\Booking`
- `Hapio\Sdk\Models\BookableSlot`
- `Hapio\Sdk\Models\TimeSpan`
- `Hapio\Sdk\Models\ResourceServiceAssociation`

These models are returned when fetching entities, as well as when you create or update entities. When instantiating a new model, you can provide an array of properties to set:

```php
$location = new Location([
    'name' => 'My first location',
    'time_zone' => 'Europe/Stockholm',
    'resource_selection_strategy' => 'equalize',
]);
```

You can also set and get individual properties on the model object:

```php
$location->name = 'My first location';

echo $location->name; // "My first location"
```

You can use either `snake_case` or `camelCase` for the property names. Property names in `camelCase` will be automatically converted to `snake_case` internally, since that is used when communicating with the API.

```php
$location->timeZone = 'Europe/London';

echo $location->timeZone; // "Europe/London"
echo $location->time_zone; // "Europe/London"
```

You can only set values for valid property names:

```php
$location->nonExistentAttribute = 'A random value';

echo $location->nonExistentAttribute; // null
```

### Repositories

The Hapio SDK provides a repository class for each type of entity available in Hapio:

- `Hapio\Sdk\Repositories\ProjectRepository`
- `Hapio\Sdk\Repositories\LocationRepository`
- `Hapio\Sdk\Repositories\ResourceRepository`
- `Hapio\Sdk\Repositories\ServiceRepository`
- `Hapio\Sdk\Repositories\ScheduleBlockRepository`
- `Hapio\Sdk\Repositories\RecurringScheduleRepository`
- `Hapio\Sdk\Repositories\RecurringScheduleBlockRepository`
- `Hapio\Sdk\Repositories\BookingRepository`

These repositories are the communication gateway to the API, and are used to send and retrieve data.

The repositories are accessible through methods on the `ApiClient` class:

```php
public function projects(): ProjectRepository;
public function locations(): LocationRepository;
public function resources(): ResourceRepository;
public function services(): ServiceRepository;
public function scheduleBlocks(): ScheduleBlockRepository;
public function recurringSchedules(): RecurringScheduleRepository;
public function recurringScheduleBlocks(): RecurringScheduleBlockRepository;
public function bookings(): BookingRepository;
```

Each repository has a set of methods, corresponding to the endpoints available for the entity. These are listed for each repository below.

#### Hapio\Sdk\Repositories\ProjectRepository

```php
public function getCurrentProject(): Project;
```

#### Hapio\Sdk\Repositories\LocationRepository

```php
public function list(array $params = []): PaginatedResponse;
public function get(string $id): Location;
public function store(Location $location): Location;
public function replace(string $id, Location $location): Location;
public function patch(string $id, Location $location): Location;
public function delete(string $id): bool;
```

#### Hapio\Sdk\Repositories\ResourceRepository

```php
public function list(array $params = []): PaginatedResponse;
public function get(string $id): Resource;
public function store(Resource $resource): Resource;
public function replace(string $id, Resource $resource): Resource;
public function patch(string $id, Resource $resource): Resource;
public function delete(string $id): bool;
public function listSchedule(string $id, array $params = []): PaginatedResponse;
public function listFullyBooked(string $id, array $params = []): PaginatedResponse;
public function listAssociatedServices(string $resourceId): array;
public function getAssociatedService(string $resourceId, string $serviceId): ResourceServiceAssociation;
public function associateService(string $resourceId, string $serviceId): ResourceServiceAssociation;
public function dissociateService(string $resourceId, string $serviceId): bool;
```

#### Hapio\Sdk\Repositories\ServiceRepository

```php
public function list(array $params = []): PaginatedResponse;
public function get(string $id): Service;
public function store(Service $service): Service;
public function replace(string $id, Service $service): Service;
public function patch(string $id, Service $service): Service;
public function delete(string $id): bool;
public function listBookableSlots(string $serviceId, array $params = []): PaginatedResponse;
public function listBookableSlots(string $serviceId, array $params = []): PaginatedResponse;
public function listAssociatedResources(string $serviceId): array;
public function getAssociatedResource(string $serviceId, string $resourceId): ResourceServiceAssociation;
public function associateResource(string $serviceId, string $resourceId): ResourceServiceAssociation;
public function dissociateResource(string $serviceId, string $resourceId): bool;
```

#### Hapio\Sdk\Repositories\ScheduleSlotRepository

```php
public function list(array $parentIds, array $params = []): PaginatedResponse;
public function get(array $parentIds, string $id): ScheduleBlock;
public function store(array $parentIds, Service $service): ScheduleBlock;
public function replace(array $parentIds, string $id, Service $service): ScheduleBlock;
public function patch(array $parentIds, string $id, Service $service): ScheduleBlock;
public function delete(array $parentIds, string $id): bool;
```

#### Hapio\Sdk\Repositories\RecurringScheduleRepository


```php
public function list(array $parentIds, array $params = []): PaginatedResponse;
public function get(array $parentIds, string $id): RecurringSchedule;
public function store(array $parentIds, RecurringSchedule $service): RecurringSchedule;
public function replace(array $parentIds, string $id, RecurringSchedule $service): RecurringSchedule;
public function patch(array $parentIds, string $id, RecurringSchedule $service): RecurringSchedule;
public function delete(array $parentIds, string $id): bool;
```

#### Hapio\Sdk\Repositories\RecurringScheduleBlockRepository


```php
public function list(array $parentIds, array $params = []): PaginatedResponse;
public function get(array $parentIds, string $id): RecurringScheduleBlock;
public function store(array $parentIds, Service $service): RecurringScheduleBlock;
public function replace(array $parentIds, string $id, Service $service): RecurringScheduleBlock;
public function patch(array $parentIds, string $id, Service $service): RecurringScheduleBlock;
public function delete(array $parentIds, string $id): bool;
```

### Paginated responses

All of the methods for API endpoints that respond with a paginated list, will return an instance of `Hapio\Sdk\PaginatedResponse`. This class has a set of methods that make it easier for you to work with these paginated responses.

```php
public function getItems(): ArrayIterator;
public function hasMoreItems(): bool;
public function getNextPage(): PaginatedResponse|null;
public function getPreviousPage(): PaginatedResponse|null;
public function getFirstPage(): PaginatedResponse|null;
public function getLastPage(): PaginatedResponse|null;
public function getCurrentPageNumber(): int;
public function getLastPageNumber(): int;
public function getFromIndex(): int;
public function getToIndex(): int;
public function getTotalItems(): int;
public function getItemsPerPage(): int;
public function getNextPageLink(): string|null;
public function getPreviousPageLink(): string|null;
public function getFirstPageLink(): string|null;
public function getLastPageLink(): string|null;
```

### Exceptions

Whenever the API responds with a `4XX` or `5XX` response, an exception will be thrown. Validation error responses with a status code of `422` will throw a `Hapio\Sdk\Exceptions\ValidationException`, and all other `4XX` and `5XX` status codes will throw a `Hapio\Sdk\Exceptions\ErrorException`. For both of these exceptions you can access the original Guzzle exception using the `getPrevious()` method, the `message` property of the response is available using the `getMessage()` method, and the status code can be fetched using the `getCode()` method. The `ValidationException` class also has a method for accessing validation errors, called `getValidationErrors()`. Other Guzzle exceptions are not caught by the SDK.

```php
$resource = new Resource([
    'name' => '',
]);

try {
    $resourceRepository->store($resource);
} catch (ValidationException $e) {
    $e->getCode(); // 422
    $e->getMessage(); // "The given data was invalid."
    $e->getValidationErrors(); // ["name" => ["The name field is required."]]
}
```

## Resources

- [API documentation](https://docs.hapio.io/)
