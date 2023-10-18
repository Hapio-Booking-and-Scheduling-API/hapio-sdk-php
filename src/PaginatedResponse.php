<?php

namespace Hapio\Sdk;

use ArrayIterator;
use Hapio\Sdk\Repositories\CrudRepositoryInterface;

class PaginatedResponse
{
    /**
     * A callback function used to follow links in the response.
     *
     * @var callable
     */
    protected $followLinkCallback;

    /**
     * The items in the response.
     *
     * @var array
     */
    protected $items;

    /**
     * The number of the current page.
     *
     * @var int
     */
    protected $currentPageNumber;

    /**
     * The number of the last page.
     *
     * @var int
     */
    protected $lastPageNumber;

    /**
     * The index of the first item on this page.
     *
     * @var int
     */
    protected $fromIndex;

    /**
     * The index of the last item on this page.
     *
     * @var int
     */
    protected $toIndex;

    /**
     * The number of items per page.
     *
     * @var int
     */
    protected $numPerPage;

    /**
     * The total number of items across all pages.
     *
     * @var int
     */
    protected $numTotal;

    /**
     * The links to other pages.
     *
     * @var array
     */
    protected $links;

    /**
     * Constructor.
     *
     * @param array    $response           The response data.
     * @param callable $followLinkCallback A callback function used to follow links in the response.
     */
    public function __construct(array $response, callable $followLinkCallback)
    {
        $this->followLinkCallback = $followLinkCallback;
        $this->items = new ArrayIterator($response['data']);
        $this->currentPageNumber = $response['meta']['current_page'];
        $this->currentPageNumber = $response['meta']['current_page'];
        $this->lastPageNumber = $response['meta']['last_page'];
        $this->fromIndex = $response['meta']['from'];
        $this->toIndex = $response['meta']['to'];
        $this->numPerPage = $response['meta']['per_page'];
        $this->numTotal = $response['meta']['total'];

        $this->links = [
            'first' => $response['links']['first'],
            'last' => $response['links']['last'],
            'previous' => $response['links']['prev'],
            'next' => $response['links']['next'],
        ];
    }

    /**
     * Get the items of this page.
     *
     * @return ArrayIterator
     */
    public function getItems(): ArrayIterator
    {
        return $this->items;
    }

    /**
     * Determine whether there are more items after this page.
     *
     * @return bool
     */
    public function hasMoreItems(): bool
    {
        return $this->getCurrentPageNumber() < $this->getLastPageNumber();
    }

    /**
     * Get the next page.
     *
     * @return static|null
     */
    public function getNextPage(): static|null
    {
        return $this->followLink('next');
    }

    /**
     * Get the previous page.
     *
     * @return static|null
     */
    public function getPreviousPage(): static|null
    {
        return $this->followLink('previous');
    }

    /**
     * Get the first page.
     *
     * @return static|null
     */
    public function getFirstPage(): static|null
    {
        return $this->followLink('first');
    }

    /**
     * Get the last page.
     *
     * @return static|null
     */
    public function getLastPage(): static|null
    {
        return $this->followLink('last');
    }

    /**
     * Follow a link in the response.
     *
     * @param string $link The name of the link.
     *
     * @return static|null
     */
    protected function followLink($link): static|null
    {
        return ($this->followLinkCallback)($link);
    }

    /**
     * Get the number of the current page.
     *
     * @return int
     */
    public function getCurrentPageNumber(): int
    {
        return $this->currentPageNumber;
    }

    /**
     * Get the number of the last page.
     *
     * @return int
     */
    public function getLastPageNumber(): int
    {
        return $this->lastPageNumber;
    }

    /**
     * Get the index of the first item on this page.
     *
     * @return int
     */
    public function getFromIndex(): int
    {
        return $this->fromIndex;
    }

    /**
     * Get the index of the last item on this page.
     *
     * @return int
     */
    public function getToIndex(): int
    {
        return $this->toIndex;
    }

    /**
     * Get the total number of items across all pages.
     *
     * @return int
     */
    public function getTotalItems(): int
    {
        return $this->numTotal;
    }

    /**
     * Get the number of items per page.
     *
     * @return int
     */
    public function getItemsPerPage(): int
    {
        return $this->numPerPage;
    }

    /**
     * Get the link to the next page.
     *
     * @return string|null
     */
    public function getNextPageLink(): string|null
    {
        return $this->getLink('next');
    }

    /**
     * Get the link to the previous page.
     *
     * @return string|null
     */
    public function getPreviousPageLink(): string|null
    {
        return $this->getLink('previous');
    }

    /**
     * Get the link to the first page.
     *
     * @return string|null
     */
    public function getFirstPageLink(): string|null
    {
        return $this->getLink('first');
    }

    /**
     * Get the link to the last page.
     *
     * @return string|null
     */
    public function getLastPageLink(): string|null
    {
        return $this->getLink('last');
    }

    /**
     * Get a link from the response.
     *
     * @param string $link The name of the link.
     *
     * @return string|null
     */
    protected function getLink($link): string|null
    {
        return $this->links[$link];
    }
}