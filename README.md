[![Latest Version on Packagist](https://img.shields.io/packagist/v/mynetx/umami?style=flat-square)](https://packagist.org/packages/mynetx/umami)
[![GitHub Release](https://img.shields.io/github/v/release/mynetx/umami?style=flat-square)](https://github.com/mynetx/umami/releases)
[![License](https://img.shields.io/github/license/mynetx/umami?style=flat-square)](https://github.com/mynetx/umami/blob/main/LICENSE)

# Umami Analytics for Statamic

This Statamic addon makes it easy to connect your site to a self-hosted [Umami](https://umami.is) instance.

It provides:

- 🧩 A tag to embed the Umami tracking script on your front-end pages
- 📊 A Control Panel widget to view your Umami stats right inside Statamic

---

## Features

✅ Add tracking to your Statamic templates with `{{ umami }}`  
✅ Show Umami stats like visitors, page views, bounce rate, and visit duration  
✅ Configure via environment variables and the `config/statamic/cp.php` widget array  
✅ Multi-language support (English, German)  
🚧 Time range selection coming soon

---

## Requirements

- Statamic 5.x
- PHP 8.2 or higher
- A running, self-hosted Umami instance with API access

> This addon currently works with self-hosted Umami only. Umami Cloud with API keys is not supported yet.

---

## Installation

Install via Composer:

```bash
composer require mynetx/umami
```

---

## Configuration

### Environment Variables

Add these to your `.env` file:

```dotenv
UMAMI_HOST=https://analytics.example.com
UMAMI_USERNAME=your-username
UMAMI_PASSWORD=your-password
UMAMI_WEBSITE_ID=your-website-id
UMAMI_TEAM_ID=your-team-id
```

| Variable           | Description                     | Example                      |
|--------------------|---------------------------------|------------------------------|
| `UMAMI_HOST`       | Your Umami base URL             | https://analytics.example.com |
| `UMAMI_USERNAME`   | Umami login username            | admin                        |
| `UMAMI_PASSWORD`   | Umami password                  | secure_password              |
| `UMAMI_WEBSITE_ID` | Website ID from your Umami site | abc123                       |
| `UMAMI_TEAM_ID`    | Team ID (if applicable)         | abc123                       |

Find your website and team IDs in the URL when viewing your Umami dashboard.

---

### Widget Setup

To display stats in the Control Panel dashboard:

1. Open `config/statamic/cp.php`
2. Locate the `widgets` array
3. Add an entry for the Umami widget:

```php
'widgets' => [
    [
        'type' => 'umami_stats',
        'title' => 'My Umami Stats',
        'host' => env('UMAMI_HOST'),
        'username' => env('UMAMI_USERNAME'),
        'password' => env('UMAMI_PASSWORD'),
        'website_id' => env('UMAMI_WEBSITE_ID'),
        'team_id' => env('UMAMI_TEAM_ID'),
        'timeframe' => '7d', // 24h, 7d, 30d, or 90d
    ],
],
```

💡 You can hardcode values or pull them from environment variables.

---

### Tracking Setup

To embed the Umami tracking script in your site, add this to your Antlers layout or template:

```antlers
{{ umami }}
```

or explicitly:

```antlers
{{ umami:script }}
```

The script is only included when the current environment matches the `enabled_environments` config setting:

```php
'enabled_environments' => ['production'],
```

Set it to `null` to allow output on all environments.

---

## How It Works

This addon connects to your Umami instance via these API endpoints:

- `POST /api/auth/login` — Authenticate and retrieve an access token
- `GET /api/websites/{websiteId}/stats` — Fetch your website’s analytics

Refer to the [Umami API documentation](https://umami.is/docs/api) for more details.

---

## License

MIT License
