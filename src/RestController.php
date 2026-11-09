<?php

declare(strict_types=1);

namespace ImmoKit;

use WP_REST_Request;
use WP_REST_Response;

/**
 * Stellt die Suche als REST-Endpunkt bereit:
 * GET /wp-json/immo-kit/v1/immobilien?ort=Aachen&art=miete&maxPreis=1000&minZimmer=2
 */
final class RestController
{
    public function __construct(private readonly ImmobilienSuche $suche)
    {
    }

    public function registriere(): void
    {
        register_rest_route('immo-kit/v1', '/immobilien', [
            'methods'             => 'GET',
            'callback'            => [$this, 'liste'],
            'permission_callback' => '__return_true', // öffentliche Liste, jeder darf lesen
            'args'                => [
                'ort'       => ['type' => 'string',  'sanitize_callback' => 'sanitize_text_field'],
                'art'       => ['type' => 'string',  'sanitize_callback' => 'sanitize_key'],
                'maxPreis'  => ['type' => 'integer', 'sanitize_callback' => 'absint'],
                'minZimmer' => ['type' => 'integer', 'sanitize_callback' => 'absint'],
            ],
        ]);
    }

    public function liste(WP_REST_Request $request): WP_REST_Response
    {
        $filter = new Filter(
            ort: $request->get_param('ort'),
            art: $request->get_param('art'),
            maxPreis: $request->get_param('maxPreis'),
            minZimmer: $request->get_param('minZimmer'),
        );

        return new WP_REST_Response($this->suche->finde($filter));
    }
}
