<?php

declare(strict_types=1);

namespace ImmoKit;

/**
 * Suchkriterien des Besuchers. Jeder Wert ist optional, null heißt "egal".
 */
final readonly class Filter
{
    public function __construct(
        public ?string $ort = null,
        public ?string $art = null,
        public ?int $maxPreis = null,
        public ?int $minZimmer = null,
    ) {
    }
}
