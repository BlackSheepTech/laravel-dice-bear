<?php

namespace BlackSheepTech\DiceBear\Styles;

use BlackSheepTech\DiceBear\DiceBear;
use Symfony\Component\HttpFoundation\StreamedResponse;

class adventurerNeutral extends DiceBear
{
    private string $name = 'adventurer-neutral';

    private string $eyebrows;

    private string $eyes;

    private string $mouth;

    private string $glasses;

    private int $glassesProbability;

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
        return array_merge(
            parent::buildBaseQueryParams(),
            isset($this->eyebrows) ? ['eyebrows' => $this->eyebrows] : [],
            isset($this->eyes) ? ['eyes' => $this->eyes] : [],
            isset($this->mouth) ? ['mouth' => $this->mouth] : [],
            isset($this->glasses) ? ['glasses' => $this->glasses] : [],
            isset($this->glassesProbability) ? ['glassesProbability' => $this->glassesProbability] : []
        );
    }
}
