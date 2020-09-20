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
        $handle = @fopen($this->dataPath, "r");
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

        $handle = @fopen($configPath, "r");
        if ($handle === false) {
            throw new Exception("Config $configPath not found");
        }

        while (($configKey = fgets($handle)) !== false) {
            $parsedConfigKey = $this->parseConfigKey($configKey);
            $this->redactDataForConfigKey($parsedConfigKey, $jsonData);
        }

        fclose($handle);

        $encodedData = json_encode($jsonData);
        $this->data .= $this->data === "" ? $encodedData : "\n$encodedData";
    }

    private function parseConfigKey(string $configKey): array {
        return explode('.', trim($configKey));
    }

    private function redactDataForConfigKey(array $configKey, &$jsonData): void {
        $firstConfigKey = array_shift($configKey);

        //1st level
        $this->redactDataForFirstLevel($configKey, $firstConfigKey, $jsonData);

        //2...nth levels
        $this->redactDataForNextLevels($configKey, $firstConfigKey, $jsonData);
    }

    private function redactDataForFirstLevel(array $configKey, string $firstConfigKey, &$jsonData) {
        if  (\array_key_exists($firstConfigKey, $jsonData)) {
            if (count($configKey) > 0) {
                $this->redactDataForConfigKey($configKey, $jsonData[$firstConfigKey]);
            } else {
                $jsonData[$firstConfigKey] = self::REDACTED_CONTENT;
            }
        }
    }

    private function redactDataForNextLevels(array $configKey, string $firstConfigKey, &$jsonData): void {
        array_walk_recursive($jsonData, function(&$item, $key) use ($firstConfigKey, $configKey) {
            if ($key === $firstConfigKey) {
                if (count($configKey) > 0) {
                    if (is_array($item)) {
                        $this->redactDataForConfigKey($configKey, $item);
                    }
                } else {
                    $item = self::REDACTED_CONTENT;
                }
            }
        });
    }

    public function data(): string {
        return $this->data;
    }
}
