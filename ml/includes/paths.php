<?php

declare(strict_types=1);

/**
 * URL helpers for static assets and internal pages under `/ml/`.
 * Document root should be the parent of the `ml/` folder (e.g. site root).
 *
 * Example URLs: /ml , /ml/ml_any , /ml/ml_test
 */
function app_public_base(): string
{
    $script = $_SERVER['SCRIPT_NAME'] ?? '/';
    $script = str_replace('\\', '/', (string) $script);
    $dir = dirname($script);
    if ($dir === '/' || $dir === '.' || $dir === '\\') {
        return '';
    }

    return rtrim($dir, '/');
}

/** Web path to a file under `ml/assets/`. */
function asset(string $relativePath): string
{
    $relativePath = str_replace('\\', '/', ltrim($relativePath, '/'));

    return app_public_base() . '/assets/' . $relativePath;
}

/**
 * Clean URL for a sibling PHP script in this directory (no `.php` in URL;
 * requires mod_rewrite in `ml/.htaccess`).
 */
function page_url(string $slug): string
{
    if (!preg_match('/^[a-zA-Z0-9_-]+$/', $slug)) {
        throw new InvalidArgumentException('Invalid page slug.');
    }

    return app_public_base() . '/' . $slug;
}
