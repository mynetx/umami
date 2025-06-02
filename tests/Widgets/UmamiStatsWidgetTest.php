<?php

namespace Mynetx\Umami\Tests\Widgets;

use Mynetx\Umami\Tests\TestCase;
use Mynetx\Umami\Tests\Widgets\Helpers\UmamiStatsWidgetTestable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Mockery;

class UmamiStatsWidgetTest extends TestCase
{
    protected UmamiStatsWidgetTestable $widget;

    protected function setUp(): void
    {
        parent::setUp();
        $this->widget = new UmamiStatsWidgetTestable();
        $this->app->make('view')->addNamespace('mynetx-umami', realpath(__DIR__ . '/../../resources/views'));
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('umami.host', 'https://example.com');
        $app['config']->set('umami.username', 'user');
        $app['config']->set('umami.password', 'pass');
        $app['config']->set('umami.website_id', '11111111-1111-1111-1111-111111111111');
        $app['config']->set('umami.team_id', '22222222-2222-2222-2222-222222222222');
        $app['config']->set('umami.timeframe', '7d');
    }

    public function testHtmlReturnsViewWithCorrectKeys()
    {
        $view = $this->widget->html();
        $this->assertArrayHasKey('title', $view->getData());
        $this->assertArrayHasKey('stats', $view->getData());
        $this->assertArrayHasKey('error', $view->getData());
        $this->assertArrayHasKey('externalDashboardUrl', $view->getData());
    }

    public function testGetAuthTokenReturnsNullIfConfigMissing()
    {
        config(['umami.host' => null, 'umami.username' => null, 'umami.password' => null]);
        $result = $this->widget->callGetAuthToken();
        $this->assertNull($result);
    }

    public function testGetAuthTokenReturnsTokenOnSuccessfulResponse()
    {
        config(['umami.host' => 'https://example.com', 'umami.username' => 'user', 'umami.password' => 'pass']);

        Http::fake([
            'https://example.com/*' => fn ($request) => Http::response(['token' => 'token123']),
        ]);

        $result = $this->widget->callGetAuthToken();
        $this->assertSame('token123', $result);
    }

    public function testFetchStatsReturnsEmptyWhenInvalidConfig()
    {
        config(['umami.host' => null, 'umami.website_id' => 'invalid-uuid']);
        $result = $this->widget->callFetchStats('');
        $this->assertSame([], $result);
    }

    public function testFetchStatsReturnsDataOnSuccessfulResponse()
    {
        config([
            'umami.host' => 'https://example.com',
            'umami.website_id' => '11111111-1111-1111-1111-111111111111',
            'umami.timeframe' => '7d',
        ]);

        Http::fake([
            'https://example.com/api/websites/11111111-1111-1111-1111-111111111111/stats*' =>
                Http::response(['visits' => 10], 200),
        ]);

        $result = $this->widget->callFetchStats('');
        $this->assertArrayHasKey('visits', $result);
        $this->assertArrayHasKey('startAt', $result);
        $this->assertArrayHasKey('endAt', $result);
    }

    public function testNormalizeHost()
    {
        $this->assertSame('https://example.com', $this->widget->callNormalizeHost('https://example.com/'));
        $this->assertSame('https://example.com', $this->widget->callNormalizeHost('https://example.com'));
        $this->assertNull($this->widget->callNormalizeHost(null));
    }

    public function testGetExternalDashboardUrl()
    {
        config([
            'umami.host' => 'https://example.com',
            'umami.website_id' => '11111111-1111-1111-1111-111111111111',
            'umami.team_id' => '22222222-2222-2222-2222-222222222222',
        ]);

        $url = $this->widget->callGetExternalDashboardUrl();
        $this->assertSame('https://example.com/teams/22222222-2222-2222-2222-222222222222/websites/11111111-1111-1111-1111-111111111111', $url);

        // Test no team id
        config(['umami.team_id' => null]);
        $url2 = $this->widget->callGetExternalDashboardUrl();
        $this->assertSame('https://example.com/websites/11111111-1111-1111-1111-111111111111', $url2);

        // Test invalid UUID
        config(['umami.website_id' => 'invalid']);
        $this->assertNull($this->widget->callGetExternalDashboardUrl());
    }

    public function testConfigFieldsReturnsExpectedKeys()
    {
        $fields = $this->widget->configFields();
        $this->assertArrayHasKey('title', $fields);
        $this->assertArrayHasKey('host', $fields);
        $this->assertArrayHasKey('username', $fields);
        $this->assertArrayHasKey('password', $fields);
        $this->assertArrayHasKey('website_id', $fields);
        $this->assertArrayHasKey('team_id', $fields);
        $this->assertArrayHasKey('timeframe', $fields);
    }
}
