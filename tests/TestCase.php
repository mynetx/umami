<?php

namespace Mynetx\Umami\Tests;

use Mynetx\Umami\ServiceProvider;
use Statamic\Testing\AddonTestCase;

abstract class TestCase extends AddonTestCase
{
    protected string $addonServiceProvider = ServiceProvider::class;
}
