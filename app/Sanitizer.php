<?php

declare(strict_types=1);

namespace App;

use Exception;

class Sanitizer
{
    private $dataPath;
    private $configPath;
    private $data;

    public function __construct(string $configPath, string $dataPath) {
        $this->configPath = $configPath;
        $this->dataPath = $dataPath;
        $this->data = "";
    }

    public function sanitize(): void {
        $dataHandle = fopen($this->dataPath, "r");
        if ($dataHandle) {
            while (($data = fgets($dataHandle)) !== false) {
                $data = trim($data);
                $jsonData = json_decode($data, true);
                $configHandle = fopen($this->configPath, "r");
                if ($configHandle) {
                    while (($configKey = fgets($configHandle)) !== false) {
                        $configKey = trim($configKey);
                        if (\array_key_exists($configKey, $jsonData)) {
                            $jsonData[$configKey] = "******";
                        }

                        array_walk_recursive($jsonData, function(&$item, $key) use ($configKey) {
                            if ($key === $configKey) {
                                $item = "******";
                            }
                        });
                    }

                    fclose($configHandle);

                    $encodedData = json_encode($jsonData);
                    $this->data .= $this->data === "" ? $encodedData : "\n$encodedData";
                } else {
                    throw new Exception("Config $this->configPath not found");
                }
            }
            fclose($dataHandle);
        } else {
            throw new Exception("Data $this->dataPath not found");
        }
    }

    public function data(): string {
        return $this->data;
    }
}
