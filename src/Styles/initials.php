<?php

namespace BlackSheepTech\DiceBear\Styles;

use BlackSheepTech\DiceBear\DiceBear;
use Symfony\Component\HttpFoundation\StreamedResponse;

class initials extends DiceBear
{
    private string $name = 'initials';

    // Output generation
    public function getUrl(): string
    {
        return $this->baseUrl.$this->name.'/'.$this->format.'?'.http_build_query($this->buildQueryParams());
    }

    public function download(?string $fileName = null): StreamedResponse
    {
        return parent::outputDownload($this->getUrl(), $fileName);

    }

    public function saveTo(string $path, ?string $fileName = null, ?string $disk = null): string|false
    {
        return parent::outputSaveTo($path, $fileName, $disk);
    }

    private function buildQueryParams(): array
    {
        return parent::buildBaseQueryParams();
    }
}
