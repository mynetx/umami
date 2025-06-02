<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Umami Host
    |--------------------------------------------------------------------------
    |
    | The URL of your Umami instance (e.g., https://analytics.example.com).
    |
    */
    'host' => env('UMAMI_HOST', null),

    /*
    |--------------------------------------------------------------------------
    | Umami Website ID
    |--------------------------------------------------------------------------
    |
    | The ID of your website in Umami.
    |
    */
    'website_id' => env('UMAMI_WEBSITE_ID', null),

    /*
    |--------------------------------------------------------------------------
    | Umami Team ID
    |--------------------------------------------------------------------------
    |
    | The ID of your team in Umami.
    |
    */
    'team_id' => env('UMAMI_TEAM_ID', null),

    /*
    |--------------------------------------------------------------------------
    | Umami Authentication
    |--------------------------------------------------------------------------
    |
    | Credentials for authenticating with the Umami API.
    | These are used for the dashboard widget.
    |
    */
    'username' => env('UMAMI_USERNAME', null),
    'password' => env('UMAMI_PASSWORD', null),

    /*
    |--------------------------------------------------------------------------
    | Environment Restriction
    |--------------------------------------------------------------------------
    |
    | Only include the tracking script in the specified environments.
    | Set to null to include in all environments.
    |
    */
    'enabled_environments' => ['production'],
];
