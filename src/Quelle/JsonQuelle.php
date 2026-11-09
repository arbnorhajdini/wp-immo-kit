<?php

declare(strict_types=1);

namespace ImmoKit\Quelle;

final class JsonQuelle implements ImmobilienQuelle
{
    public function __construct(private readonly string $datei)
    {
    }

    public function alle(): array
    {
        if (!is_readable($this->datei)) {
            return [];
        }

        $daten = json_decode((string) file_get_contents($this->datei), true);

        return is_array($daten) ? $daten : [];
    }
}