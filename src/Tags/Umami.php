<?php

namespace Mynetx\Umami\Tags;

use Statamic\Tags\Tags;

class Umami extends Tags
{
    /**
     * The {{ umami }} tag, shortcut for the {{ umami:script }} tag.
     *
     * @return string
     */
    public function index(): string
    {
        return $this->script();
    }

    /**
     * The {{ umami:script }} tag.
     *
     * @return string
     */
    public function script(): string
    {
        $host = $this->normalizeHost(config('umami.host'));
        $websiteId = config('umami.website_id');
        $enabledEnvironments = config('umami.enabled_environments', ['production']);

        // Skip if the host or website ID are not set
        if (!$host || !$websiteId) {
            return '';
        }

        // Skip if current environment is not enabled
        if ($enabledEnvironments !== null && !in_array(app()->environment(), $enabledEnvironments)) {
            return '';
        }

        return '<script defer src="' . $host . '/script.js" data-website-id="' . $websiteId . '"></script>';
    }

    /**
     * Normalize the host URL by removing trailing slashes.
     *
     * @param string|null $host
     * @return string|null
     */
    private function normalizeHost(?string $host): ?string
    {
        if (!$host) {
            return null;
        }

        return rtrim($host, '/');
    }
}
