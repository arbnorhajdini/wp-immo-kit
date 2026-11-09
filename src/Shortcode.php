<?php

declare(strict_types=1);

namespace ImmoKit;

/**
 * [immo_kit_search titel="Immobilien finden"]
 * Gibt nur einen leeren Kasten mit Einstellungen aus und lädt das Vue-Script.
 * Vue zieht danach in diesen Kasten ein.
 */
final class Shortcode
{
    public function __construct(private readonly string $pluginDatei)
    {
    }

    public function registriere(): void
    {
        add_shortcode('immo_kit_search', [$this, 'render']);
    }

    /** @param array<string, string>|string $atts */
    public function render(array|string $atts = []): string
    {
        $atts = shortcode_atts(['titel' => 'Immobilien finden'], (array) $atts, 'immo_kit_search');

        $this->ladeDateien();

        $props = [
            'restUrl' => rest_url('immo-kit/v1/immobilien'),
            'titel'   => sanitize_text_field($atts['titel']),
        ];

        return '<div class="immo-kit" data-immo-kit-search="' . esc_attr(wp_json_encode($props)) . '"></div>';
    }

    private function ladeDateien(): void
    {
        $url  = plugin_dir_url($this->pluginDatei) . 'dist/';
        $pfad = plugin_dir_path($this->pluginDatei) . 'dist/';

        $js = $pfad . 'immo-kit.js';
        wp_enqueue_script('immo-kit', $url . 'immo-kit.js', [], $this->version($js), true);

        $css = $pfad . 'immo-kit.css';
        if (file_exists($css)) {
            wp_enqueue_style('immo-kit', $url . 'immo-kit.css', [], $this->version($css));
        }
    }

    /** Änderungsdatum als Version: nach jedem Build lädt der Browser die neue Datei. */
    private function version(string $datei): string
    {
        return file_exists($datei) ? (string) filemtime($datei) : '0.1.0';
    }
}
