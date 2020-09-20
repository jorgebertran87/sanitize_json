<?php

declare(strict_types = 1);

namespace UnitTests;

use PHPUnit\Framework\TestCase;
use App\Config;
use App\Data;
use App\Sanitizer;

class DepartmentRangeTest extends TestCase
{
    /** @test */
    public function itShouldSalitizePasswordAndAuth() {
        $config = new Config('./files/config_for_pass_and_auth');
        $data = new Data('./files/data_with_pass_and_auth');
        $sanitizer = new Sanitizer($config, $data);
        $sanitizedData = $sanitizer->sanitize();
        $this->assertJsonStringEqualsJsonFile('./files/sanitized_data_with_pass_and_auth', $sanitizedData);
    }
}
