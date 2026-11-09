<?php
/**
 * Plugin Name: Immo Kit
 * Description: Immobilienlisten, Suche und Merkliste mit Vue-Frontend.
 * Version: 0.1.0
 * Requires PHP: 8.2
 * Author: Arbnor Hajdini
 * License: MIT
 * Text Domain: immo-kit
 */

defined('ABSPATH') || exit;

require_once __DIR__ . '/vendor/autoload.php';

use ImmoKit\ImmobilienSuche;
use ImmoKit\Quelle\CacheQuelle;
use ImmoKit\Quelle\JsonQuelle;
use ImmoKit\RestController;
use ImmoKit\Shortcode;

/**
 * Hier werden alle Teile zusammengesteckt (wie der DI-Container bei onOffice).
 */
function immo_kit_suche(): ImmobilienSuche
{
    $quelle = new CacheQuelle(new JsonQuelle(__DIR__ . '/daten/immobilien.json'));

    return new ImmobilienSuche($quelle);
}

// REST-Endpunkt anmelden
add_action('rest_api_init', function (): void {
    (new RestController(immo_kit_suche()))->registriere();
});

// Shortcode [immo_kit_search] anmelden
add_action('init', function (): void {
    (new Shortcode(__FILE__))->registriere();
});
