<?php

namespace Mynetx\Umami\Tests\Widgets\Helpers;

use Mynetx\Umami\Widgets\UmamiStatsWidget;

class UmamiStatsWidgetTestable extends UmamiStatsWidget {
    public function callGetAuthToken(): ?string
    {
        return $this->getAuthToken();
    }

    public function callFetchStats(string $token): array
    {
        return $this->fetchStats($token);
    }

    public function callNormalizeHost(?string $host): ?string
    {
        return $this->normalizeHost($host);
    }

    public function callGetExternalDashboardUrl(): ?string
    {
        return $this->getExternalDashboardUrl();
    }
}
