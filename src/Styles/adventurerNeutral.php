<?php

namespace BlackSheepTech\DiceBear\Styles;

use BlackSheepTech\DiceBear\DiceBear;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class adventurerNeutral extends DiceBear
{
    private string $eyebrows;

    private string $eyes;

    private string $mouth;

    private string $glasses;

    private int $glassesProbability;

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
        $this->eyebrows = $defaults['eyebrows'];
        $this->eyes = $defaults['eyes'];
        $this->mouth = $defaults['mouth'];
        $this->glasses = $defaults['glasses'];
        $this->glassesProbability = $defaults['glassesProbability'];
    }

    public function eyebrows(string $eyebrows): self
    {
        $this->eyebrows = $this->validateInArray($eyebrows, self::getEyebrowsVariations(), 'eyebrows');

        return $this;
    }

    public static function getEyebrowsVariations(bool $asString = false): array|string
    {
        $variations = array_map(fn ($i) => 'variant'.sprintf('%02d', $i),
            range(1, 15)
        );

        return $asString ? implode(', ', $variations) : $variations;
    }

    public function eyes(string $eyes): self
    {
        $this->eyes = $this->validateInArray($eyes, self::getEyesVariations(), 'eyes');

        return $this;
    }

    public static function getEyesVariations(bool $asString = false): array|string
    {
        $variations = array_map(fn ($i) => 'variant'.sprintf('%02d', $i),
            range(1, 26)
        );

        return $asString ? implode(', ', $variations) : $variations;
    }

    public function glasses(string $glasses): self
    {
        $this->glasses = $this->validateInArray($glasses, self::getGlassesVariations(), 'glasses');

        return $this;
    }

    public function mouth(string $mouth): self
    {
        $this->mouth = $this->validateInArray($mouth, self::getMouthVariations(), 'mouth');

        return $this;
    }

    public static function getGlassesVariations(bool $asString = false): array|string
    {
        $variations = array_map(fn ($i) => 'variant'.sprintf('%02d', $i),
            range(1, 5)
        );

        return $asString ? implode(', ', $variations) : $variations;
    }

    public function glassesProbability(int $glassesProbability): self
    {
        $this->glassesProbability = $this->validateRange($glassesProbability, 0, 100, 'glasses probability');

        return $this;
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
            $this->eyebrows & $this->eyebrows != $apiDefaults['eyebrows'] ? ['eyebrows' => $this->eyebrows] : [],
            $this->eyes & $this->eyes != $apiDefaults['eyes'] ? ['eyes' => $this->eyes] : [],
            $this->mouth & $this->mouth != $apiDefaults['mouth'] ? ['mouth' => $this->mouth] : [],
            $this->glasses & $this->glasses != $apiDefaults['glasses'] ? ['glasses' => $this->glasses] : [],
            $this->glassesProbability & $this->glassesProbability != $apiDefaults['glassesProbability'] ? ['glassesProbability' => $this->glassesProbability] : [],
        );
    }
}
