<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Blade pages use @vite; tests should not depend on a built frontend (public/build/manifest.json).
        $this->withoutVite();
    }
}
