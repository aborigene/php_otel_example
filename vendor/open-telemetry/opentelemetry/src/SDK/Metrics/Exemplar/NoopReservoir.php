<?php

declare(strict_types=1);

namespace OpenTelemetry\SDK\Metrics\Exemplar;

use OpenTelemetry\Context\Context;
use OpenTelemetry\SDK\Common\Attribute\AttributesInterface;

final class NoopReservoir implements ExemplarReservoirInterface
{
    public function offer($index, $value, AttributesInterface $attributes, Context $context, int $timestamp, int $revision): void
    {
        // no-op
    }

    public function collect(array $dataPointAttributes, int $revision, int $limit): array
    {
        return [];
    }
}
