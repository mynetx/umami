<?php

namespace Mynetx\Umami\Tests\Tags;

use Mynetx\Umami\Tags\Umami;
use Mynetx\Umami\Tests\TestCase;
use Mockery;
use ReflectionException;

class UmamiTest extends TestCase
{
    protected Umami $tag;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tag = new Umami();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    protected function getEnvironmentSetUp($app): void
    {
        // Set default config values used in tests
        $app['config']->set('umami.host', 'https://example.com');
        $app['config']->set('umami.website_id', '123');
        $app['config']->set('umami.enabled_environments', ['production', 'local', 'testing']);
        $app['config']->set('app.env', 'local');
    }

    public function testIndexReturnsScript()
    {
        $tag = $this->getMockBuilder(Umami::class)
            ->onlyMethods(['script'])
            ->getMock();

        $tag->expects($this->once())
            ->method('script')
            ->willReturn('script-output');

        $this->assertSame('script-output', $tag->index());
    }

    public function testScriptReturnsEmptyWhenHostOrWebsiteIdMissing()
    {
        // Test missing host
        config(['umami.host' => null]);
        config(['umami.website_id' => 'abc']);
        $this->assertSame('', $this->tag->script());

        // Test missing website_id
        config(['umami.host' => 'https://example.com']);
        config(['umami.website_id' => null]);
        $this->assertSame('', $this->tag->script());

        // Restore for next tests
        config(['umami.host' => 'https://example.com']);
        config(['umami.website_id' => '123']);
    }

    public function testScriptReturnsEmptyWhenEnvironmentNotEnabled()
    {
        config(['umami.enabled_environments' => ['production']]);
        config(['app.env' => 'local']); // local isn't enabled

        $this->assertSame('', $this->tag->script());

        // Restore for next tests
        config(['umami.enabled_environments' => ['production', 'local']]);
        config(['app.env' => 'local']);
    }

    public function testScriptReturnsCorrectScriptTag()
    {
        $expected = '<script defer src="https://example.com/script.js" data-website-id="123"></script>';
        $this->assertSame($expected, $this->tag->script());
    }

    /**
     * @throws ReflectionException
     */
    public function testNormalizeHostRemovesTrailingSlash()
    {
        $method = new \ReflectionMethod(Umami::class, 'normalizeHost');
        $method->setAccessible(true);

        $this->assertSame('https://example.com', $method->invoke($this->tag, 'https://example.com/'));
        $this->assertSame('https://example.com', $method->invoke($this->tag, 'https://example.com'));
        $this->assertNull($method->invoke($this->tag, null));
    }
}