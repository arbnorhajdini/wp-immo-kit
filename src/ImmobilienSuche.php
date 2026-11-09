<?php

declare(strict_types=1);

namespace ImmoKit;

use ImmoKit\Quelle\ImmobilienQuelle;

/**
 * Holt alle Immobilien aus einer Quelle und gibt nur die passenden zurück.
 */
final class ImmobilienSuche
{
    public function __construct(private readonly ImmobilienQuelle $quelle)
    {
    }

    /** @return array<int, array<string, mixed>> */
    public function finde(Filter $filter): array
    {
        $treffer = array_filter(
            $this->quelle->alle(),
            fn (array $immo): bool => $this->passt($immo, $filter)
        );

        return array_values($treffer);
    }

    /** @param array<string, mixed> $immo */
    private function passt(array $immo, Filter $filter): bool
    {
        if ($filter->ort !== null && $filter->ort !== ''
            && mb_strtolower($immo['ort']) !== mb_strtolower($filter->ort)) {
            return false;
        }

        if ($filter->art !== null && $filter->art !== '' && $immo['art'] !== $filter->art) {
            return false;
        }

        if ($filter->maxPreis !== null && $immo['preis'] > $filter->maxPreis) {
            return false;
        }

        if ($filter->minZimmer !== null && $immo['zimmer'] < $filter->minZimmer) {
            return false;
        }

        return true;
    }
}
