<?php

namespace Mynetx\Umami\Widgets;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Statamic\Widgets\Widget;

class UmamiStatsWidget extends Widget
{
    /**
     * The HTML that should be shown in the widget.
     *
     * @return string|View
     */
    public function html(): string|View
    {
        $token = $this->getAuthToken();
        $stats = [];
        $host = $this->normalizeHost($this->config('host', config('umami.host')));
        $websiteId = $this->config('website_id', config('umami.website_id'));

        if ($token) {
            $stats = $this->fetchStats($token);
        }

        return view('mynetx-umami::widgets.umami-stats', [
            'title' => $this->config('title', 'Umami Stats'),
            'stats' => $stats,
            'error' => empty($token) ? 'Unable to authenticate with Umami' : null,
            'host' => $host,
            'websiteId' => $websiteId,
        ]);
    }

    /**
     * Get authentication token from Umami API.
     *
     * @return string|null
     */
    private function getAuthToken(): ?string
    {
        if (session()->has('umami_token')) {
            return session('umami_token');
        }

        $host = $this->normalizeHost($this->config('host', config('umami.host')));
        $username = $this->config('username', config('umami.username'));
        $password = $this->config('password', config('umami.password'));

        if (!$host || !$username || !$password) {
            return null;
        }

        try {
            $response = Http::post("{$host}/api/auth/login", [
                'username' => $username,
                'password' => $password,
            ]);

            if ($response->successful()) {
                $responseData = $response->json();

                if (json_last_error() !== JSON_ERROR_NONE) {
                    \Log::error('Umami authentication JSON parse error', [
                        'error' => json_last_error_msg(),
                        'response' => $response->body(),
                    ]);
                    return null;
                }

                $token = $responseData['token'] ?? null;

                if ($token) {
                    session(['umami_token' => $token]);
                }

                return $token;
            } else {
                \Log::error('Umami authentication failed', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Umami authentication exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        return null;
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

    /**
     * Fetch statistics from Umami API.
     *
     * @param string $token
     * @return array
     */
    private function fetchStats($token): array
    {
        $host = $this->normalizeHost($this->config('host', config('umami.host')));
        $websiteId = $this->config('website_id', config('umami.website_id'));
        $timeframe = $this->config('timeframe', '24h');

        if (!$host || !$websiteId) {
            return [];
        }

        // Get date range for stats
        $startDate = $this->getStartDate($timeframe);
        $endDate = now()->endOfDay();

        // Convert to timestamps (milliseconds) for stats API
        $startTimestamp = $startDate->timestamp * 1000;
        $endTimestamp = $endDate->timestamp * 1000;

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->get("{$host}/api/websites/{$websiteId}/stats", [
                'startAt' => $startTimestamp,
                'endAt' => $endTimestamp,
                'type' => 'custom',
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Add date range to the response for display
                $data['startAt'] = $startDate->format('Y-m-d');
                $data['endAt'] = $endDate->format('Y-m-d');

                return $data;
            } else {
                \Log::error('Umami stats request failed', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Umami stats exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        return [];
    }

    /**
     * Get start date based on timeframe.
     *
     * @param string $timeframe
     * @return Carbon
     */
    private function getStartDate($timeframe): Carbon
    {
        $date = now()->startOfDay();

        switch ($timeframe) {
            case '7d':
                return $date->subDays(7);
            case '30d':
                return $date->subDays(30);
            case '90d':
                return $date->subDays(90);
            default:
                return $date->subDay();
        }
    }

    /**
     * The parameters that the widget uses.
     *
     * @return array
     */
    public function configFields(): array
    {
        return [
            'title' => [
                'type' => 'text',
                'display' => 'Widget Title',
                'default' => 'Umami Stats',
                'instructions' => 'The title to be displayed in the widget.',
            ],
            'host' => [
                'type' => 'text',
                'display' => 'Umami Host',
                'default' => config('umami.host'),
                'instructions' => 'The URL of your Umami instance (e.g., https://analytics.example.com).',
            ],
            'username' => [
                'type' => 'text',
                'display' => 'Username',
                'default' => config('umami.username'),
                'instructions' => 'Your Umami username.',
            ],
            'password' => [
                'type' => 'text',
                'display' => 'Password',
                'default' => config('umami.password'),
                'instructions' => 'Your Umami password.',
            ],
            'website_id' => [
                'type' => 'text',
                'display' => 'Website ID',
                'default' => config('umami.website_id'),
                'instructions' => 'The ID of your website in Umami.',
            ],
            'timeframe' => [
                'type' => 'select',
                'display' => 'Timeframe',
                'default' => '24h',
                'options' => [
                    '24h' => 'Last 24 Hours',
                    '7d' => 'Last 7 Days',
                    '30d' => 'Last 30 Days',
                    '90d' => 'Last 90 Days',
                ],
                'instructions' => 'The time period for which to show statistics.',
            ],
        ];
    }
}
