<?php

declare(strict_types=1);

namespace App;

use Exception;

class Sanitizer
{
    private $dataPath;
    private $configPath;
    private $data;

    private const REDACTED_CONTENT = "******";

    public function __construct(string $configPath, string $dataPath) {
        $this->configPath = $configPath;
        $this->dataPath = $dataPath;
        $this->data = "";
    }

    public function sanitize(): void {
        $handle = fopen($this->dataPath, "r");
        if ($handle === false) {
            throw new Exception("Data $this->dataPath not found");
        }

        while (($data = fgets($handle)) !== false) {
            $this->redactDataForConfig($this->configPath, $data);
        }

        fclose($handle);
    }

    private function redactDataForConfig(string $configPath, string $data): void {
        $data = trim($data);
        $jsonData = json_decode($data, true);

        $handle = fopen($configPath, "r");
        if ($handle === false) {
            throw new Exception("Config $configPath not found");
        }

        while (($configKey = fgets($handle)) !== false) {
            $this->redactDataForConfigKey($configKey, $jsonData);
        }

        fclose($handle);

        $encodedData = json_encode($jsonData);
        $this->data .= $this->data === "" ? $encodedData : "\n$encodedData";
    }

    private function redactDataForConfigKey(string $configKey, array &$jsonData): void {
        $configKey = trim($configKey);

        if (\array_key_exists($configKey, $jsonData)) {
            $jsonData[$configKey] = self::REDACTED_CONTENT;
        }

        array_walk_recursive($jsonData, function(&$item, $key) use ($configKey) {
            if ($key === $configKey) {
                $item = self::REDACTED_CONTENT;
            }
        });
    }

    public function data(): string {
        return $this->data;
    }
}
