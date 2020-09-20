<?php

declare(strict_types = 1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Config;
use App\Data;
use App\Sanitizer;

class DepartmentRangeTest extends TestCase
{
    /** @test */
    public function itShouldSalitizePasswordAndAuth() {
        $configPath = './tests/files/config_for_pass_and_auth';
        $dataPath = './tests/files/data_with_pass_and_auth';
        $sanitizer = new Sanitizer($configPath, $dataPath);
        $sanitizer->sanitize();
        $sanitizedData = trim(\file_get_contents('./tests/files/sanitized_data_with_pass_and_auth'));
        $this->assertEquals($sanitizedData, $sanitizer->data());
    }
}
