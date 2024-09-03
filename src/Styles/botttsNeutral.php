<?php

namespace BlackSheepTech\DiceBear\Styles;

use BlackSheepTech\DiceBear\DiceBear;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class botttsNeutral extends DiceBear
{
    private string $eyes;

    private string $mouth;

    public function __construct()
    {
        $this->name = 'adventurer-neutral';
        $this->style = 'adventurerNeutral';
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

        //Style specific options default values
        $this->eyes = $defaults['eyes'];
        $this->mouth = $defaults['mouth'];
    }

    public function eyes(string $eyes): self
    {
        $this->eyes = $this->validateInArray($eyes, self::getEyesVariations(), 'eyes');

        return $this;
    }

    public static function getEyesVariations(bool $asString = false): array|string
    {
        $variations = [
            'bulging',
            'dizzy',
            'eva',
            'frame1',
            'frame2',
            'glow',
            'happy',
            'hearts',
            'robocop',
            'round',
            'roundFrame01',
            'roundFrame02',
            'sensor',
            'shade01',
        ];

        return $asString ? implode(', ', $variations) : $variations;
    }

    public function mouth(string $mouth): self
    {
        $this->mouth = self::validateInArray($mouth, self::getMouthVariations(), 'mouth');

        return $this;
    }

    public static function getMouthVariations(bool $asString = false): array|string
    {
        $variations = [
            'bite',
            'diagram',
            'grill01',
            'grill02',
            'grill03',
            'smile01',
            'smile02',
            'square01',
            'square02',
        ];

        return $asString ? implode(', ', $variations) : $variations;
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
            $this->eyes & $this->eyes != $apiDefaults['eyes'] ? ['eyes' => $this->eyes] : [],
            $this->mouth & $this->mouth != $apiDefaults['mouth'] ? ['mouth' => $this->mouth] : [],
        );
    }
}
