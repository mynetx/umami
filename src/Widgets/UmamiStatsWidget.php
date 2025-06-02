<?php

namespace Mynetx\Umami\Widgets;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Statamic\Widgets\Widget;

class UmamiStatsWidget extends Widget
{
    public function html(): string|View
    {
        $token = $this->getAuthToken();
        $stats = $token ? $this->fetchStats($token) : [];

        return view('mynetx-umami::widgets.umami-stats', [
            'title' => $this->config('title', 'Umami Stats'),
            'stats' => $stats,
            'error' => $token ? null : 'Unable to authenticate with Umami',
            'externalDashboardUrl' => $this->getExternalDashboardUrl(),
        ]);
    }

    protected function getAuthToken(): ?string
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
            $response = Http::post("{$host}/api/auth/login", compact('username', 'password'));

            if ($response->successful()) {
                $data = $response->json();

                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::error('Umami authentication JSON parse error', [
                        'error' => json_last_error_msg(),
                        'response' => $response->body(),
                    ]);
                    return null;
                }

                $token = $data['token'] ?? null;

                if ($token) {
                    session(['umami_token' => $token]);
                }

                return $token;
            }

            Log::error('Umami authentication failed', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Umami authentication exception', [
                'message' => $e->getMessage(),
            ]);
        }

        return null;
    }

    protected function fetchStats(string $token): array
    {
        $host = $this->normalizeHost($this->config('host', config('umami.host')));
        $websiteId = $this->config('website_id', config('umami.website_id'));
        $timeframe = $this->config('timeframe', '24h');

        if (!$host || !Str::isUuid($websiteId)) {
            return [];
        }

        $startDate = $this->getStartDate($timeframe);
        $endDate = now()->endOfDay();
        $params = [
            'startAt' => $startDate->timestamp * 1000,
            'endAt' => $endDate->timestamp * 1000,
            'type' => 'custom',
        ];

        try {
            $response = Http::withToken($token)->get("{$host}/api/websites/{$websiteId}/stats", $params);

            if ($response->successful()) {
                $data = $response->json();
                $data['startAt'] = $startDate->toDateString();
                $data['endAt'] = $endDate->toDateString();
                return $data;
            }

            Log::error('Umami stats request failed', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Umami stats exception', [
                'message' => $e->getMessage(),
            ]);
        }

        return [];
    }

    private function getStartDate(string $timeframe): Carbon
    {
        return match ($timeframe) {
            '7d' => now()->startOfDay()->subDays(7),
            '30d' => now()->startOfDay()->subDays(30),
            '90d' => now()->startOfDay()->subDays(90),
            default => now()->startOfDay()->subDay(),
        };
    }

    protected function normalizeHost(?string $host): ?string
    {
        return $host ? rtrim($host, '/') : null;
    }

    protected function getExternalDashboardUrl(): ?string
    {
        $host = $this->normalizeHost($this->config('host', config('umami.host')));
        $websiteId = $this->config('website_id', config('umami.website_id'));
        $teamId = $this->config('team_id', config('umami.team_id'));

        if (!$host || !Str::isUuid($websiteId)) {
            return null;
        }

        return $teamId && Str::isUuid($teamId)
            ? "{$host}/teams/{$teamId}/websites/{$websiteId}"
            : "{$host}/websites/{$websiteId}";
    }

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
            'team_id' => [
                'type' => 'text',
                'display' => 'Team ID',
                'default' => config('umami.team_id'),
                'instructions' => 'The team ID if your website belongs to a team in Umami.',
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
