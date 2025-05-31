[![Latest Version on Packagist](https://img.shields.io/packagist/v/mynetx/umami.svg?style=flat-square)](https://packagist.org/packages/mynetx/umami) <a href="https://github.com/mynetx/umami/releases">
    <img src="https://img.shields.io/github/release/mynetx/umami.svg" alt="GitHub Release" />
  </a>
  <a href="https://github.com/mynetx/umami/blob/main/LICENSE">
    <img src="https://img.shields.io/github/license/mynetx/umami.svg" alt="MIT License" />
  </a>

# Umami Analytics for Statamic

Bring your Umami analytics right into your Statamic Control Panel. Keep an eye on your website’s key stats without leaving your dashboard.

## What it does

- Shows your Umami analytics in the Statamic Control Panel
- Displays page views, unique visitors, average visit duration, and bounce rate
- Lets you choose the time range for your stats (coming soon)
- Configure via environment variables or the Control Panel

## What you need

- Statamic 5.x
- PHP 8.2 or higher
- A running Umami instance with API access

**Note:** Currently, this addon supports self-hosted Umami instances only.  
Support for Umami Cloud using API keys is planned for a future release. Stay tuned!

## Get started

Install it with Composer:

```bash
composer require mynetx/umami
```

## Set it up

### Environment variables

Add these to your `.env` file:

```env
UMAMI_HOST=https://analytics.example.com
UMAMI_USERNAME=your-username
UMAMI_PASSWORD=your-password
UMAMI_WEBSITE_ID=your-website-id
```

### What these do

- `UMAMI_HOST`: Your Umami URL (e.g., https://analytics.example.com)
- `UMAMI_USERNAME`: Your Umami login name
- `UMAMI_PASSWORD`: Your Umami password
- `UMAMI_WEBSITE_ID`: The ID of your website in Umami

You can find your website ID in the Umami dashboard URL when viewing your site’s stats (`/websites/[website-id]`).

## Add the widget

1. Open `config/statamic/cp.php`.
2. Find the `widgets` array.
3. Add `'umami_stats'` like this:

   ```php
   'widgets' => [
       'umami_stats',
   ],
   ```

## Track visits on your site

Want to track visits too? Just drop this into your template:

```antlers
{{ umami:script }}
```

Or use the shorthand:

```antlers
{{ umami }}
```

The script only loads if the current environment matches the `enabled_environments` setting (default is `production`).

### Example config

```php
'enabled_environments' => ['production'],
```

Set it to `null` to enable on all environments.

## How it works

This addon uses these Umami API endpoints:

- `/api/auth/login` — to log in
- `/api/websites/{websiteId}/stats` — to get your website stats

Learn more in the [Umami API docs](https://umami.is/docs/api).

## License

MIT License.
