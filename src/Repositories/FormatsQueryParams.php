<?php

namespace Hapio\Sdk\Repositories;

use DateTime;
use DateTimeInterface;

trait FormatsQueryParams {
	/**
	 * Format query paramteters for a request.
	 *
	 * @param array $params The query parameters.
	 *
	 * @return array
	 */
	protected function formatQueryParams(array $params): array
	{
		return array_map(function ($param) {
            if ($param instanceof DateTime) {
                return $param->format(DateTimeInterface::W3C);
            }

            return $param;
        }, $params);
	}
}