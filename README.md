# Umami Stats for Statamic

A Statamic addon that provides a Control Panel widget displaying analytics from your [Umami](https://umami.is) instance. Monitor your website's key metrics directly in your Statamic dashboard.

## Features

- Display key Umami analytics in the Statamic Control Panel
- View page views, unique visitors, average visit duration, and bounce rate
- Configurable time period (last 24 hours, 7 days, 30 days, or 90 days)
- Configure via environment variables or through the Control Panel

## Requirements

- Statamic 5.x
- PHP 8.2 or higher
- An active Umami instance with API access

## Installation

You can install this addon via Composer:

```bash
composer require mynetx/umami
```

## Configuration

### Environment Variables

Add the following variables to your `.env` file:

```env
UMAMI_HOST=https://analytics.example.com
UMAMI_USERNAME=your-username
UMAMI_PASSWORD=your-password
UMAMI_WEBSITE_ID=your-website-id
```

### Environment Variable Details

- `UMAMI_HOST`: The URL of your Umami instance (e.g., https://analytics.example.com)
- `UMAMI_USERNAME`: Your Umami username
- `UMAMI_PASSWORD`: Your Umami password
- `UMAMI_WEBSITE_ID`: The ID of your website in Umami

You can find your website ID in the Umami dashboard. It's also visible in the URL when you're viewing the website's dashboard (`/websites/[website-id]`).

## Usage

### Adding the Widget to the Control Panel

1. Open your `config/statamic/cp.php` file.
2. Locate the `widgets` array.
3. Add `'umami_stats'` to the array, like this:

   ```php
   'widgets' => [
       'umami_stats',
   ],
   ```

## API Usage

This addon uses the following Umami API endpoints:

- `/api/auth/login` - For authentication
- `/api/websites/{websiteId}/stats` - For fetching website statistics

For more information about the Umami API, refer to the [official documentation](https://umami.is/docs/api).

## License

This addon is licensed under the MIT License.
