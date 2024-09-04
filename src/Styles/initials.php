<?php

namespace BlackSheepTech\DiceBear\Styles;

use BlackSheepTech\DiceBear\DiceBear;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class initials extends DiceBear
{
    private string $name = 'initials';

    public function __construct()
    {
        $this->name = 'initials';
        $this->style = 'initials';
        $this->baseUrl = config('dice-bear.base_url', 'https://api.dicebear.com/9.x/');

        throw_unless(filter_var($this->baseUrl, FILTER_VALIDATE_URL), new \InvalidArgumentException('Invalid base URL provided.'));

        if (Str::substr($this->baseUrl, -1) != '/') {
            $this->baseUrl .= '/';
        }

        $this->baseUrl .= "{$this->name}/";
        $this->loadDefaults();
    }

    protected function loadDefaults(): void
    {
        parent::loadDefaults();

        $defaults = config("dice-bear.defaults.style.{$this->style}", []);

        throw_unless($defaults, new \InvalidArgumentException('No default values found for style options.'));
    }

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
        $apiDefaults = config("dice-bear.api-defaults.styles.{$this->style}");

        return array_merge(
            parent::buildBaseQueryParams(),
        );
    }
}
