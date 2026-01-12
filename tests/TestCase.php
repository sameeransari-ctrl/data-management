<?php

namespace Tests;

use Tests\DatabaseTableColumnCount;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseTableColumnCount;
}
