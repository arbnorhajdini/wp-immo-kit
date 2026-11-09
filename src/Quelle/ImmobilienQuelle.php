<?php

declare(strict_types=1);

namespace ImmoKit\Quelle;

interface ImmobilienQuelle
{
    /** @return array<int, array<string, mixed>> */
    public function alle(): array;
}