<?php

declare(strict_types=1);

namespace ImmoKit\Quelle;

/**
 * Decorator: legt einen Cache (WordPress-Transient) um eine andere Quelle.
 */
final class CacheQuelle implements ImmobilienQuelle
{
    private const SCHLUESSEL = 'immo_kit_immobilien';

    public function __construct(
        private readonly ImmobilienQuelle $innen,
        private readonly int $sekunden = 300,
    ) {
    }

    public function alle(): array
    {
        $gespeichert = get_transient(self::SCHLUESSEL);

        if (is_array($gespeichert)) {
            return $gespeichert;
        }

        $daten = $this->innen->alle();
        set_transient(self::SCHLUESSEL, $daten, $this->sekunden);

        return $daten;
    }
}
