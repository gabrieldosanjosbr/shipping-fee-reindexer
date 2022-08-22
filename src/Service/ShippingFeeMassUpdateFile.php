<?php

declare(strict_types=1);

namespace App\Service;

class ShippingFeeMassUpdateFile
{
    protected string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->filePath;
    }
}
