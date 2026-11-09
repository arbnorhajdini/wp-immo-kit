<?php

declare(strict_types=1);

namespace ImmoKit\Quelle;

final class TestQuelle implements ImmobilienQuelle
{
    /** @param array<int, array<string, mixed>> $immobilien */
    public function __construct(private readonly array $immobilien = [])
    {
    }

    public function alle(): array
    {
        return $this->immobilien;
    }
}