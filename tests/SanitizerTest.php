<?php

declare(strict_types = 1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Sanitizer;

class SanitizerTest extends TestCase
{
    private const CONFIG_PATH_PASS_AND_AUTH = './tests/files/config_for_pass_and_auth';
    private const DATA_PATH = './tests/files/data';
    private const CONFIG_PATH_AUTH_PASS = './tests/files/config_for_auth_pass';

    /** @test */
    public function itShouldSanitizePasswordAndAuth() {
        $sanitizer = new Sanitizer(self::CONFIG_PATH_PASS_AND_AUTH, self::DATA_PATH);
        $sanitizer->sanitize();
        $sanitizedData = trim(\file_get_contents('./tests/files/sanitized_data_with_pass_and_auth'));
        $this->assertEquals($sanitizedData, $sanitizer->data());
    }

    /** @test */
    public function itShouldThrowExceptionForWrongConfigPath() {
        $configPath = 'wrong_config_path';
        $sanitizer = new Sanitizer($configPath, self::DATA_PATH);

        $this->expectExceptionMessage("Config $configPath not found");
        $sanitizer->sanitize();
    }

    /** @test */
    public function itShouldThrowExceptionForWrongDataPath() {
        $dataPath = 'wrong_data_path';
        $sanitizer = new Sanitizer(self::CONFIG_PATH_PASS_AND_AUTH, $dataPath);

        $this->expectExceptionMessage("Data $dataPath not found");
        $sanitizer->sanitize();
    }

    /** @test */
    public function itShouldSanitizeAuthPassword() {
        $sanitizer = new Sanitizer(self::CONFIG_PATH_AUTH_PASS, self::DATA_PATH);
        $sanitizer->sanitize();
        $sanitizedData = trim(\file_get_contents('./tests/files/sanitized_data_with_auth_pass'));
        $this->assertEquals($sanitizedData, $sanitizer->data());
    }
}
