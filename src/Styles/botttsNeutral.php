<?php

namespace BlackSheepTech\DiceBear\Styles;

use BlackSheepTech\DiceBear\DiceBear;
use Symfony\Component\HttpFoundation\StreamedResponse;

class botttsNeutral extends DiceBear
{
    private string $name = 'bottts-neutral';

    private string $eyes;

    private string $mouth;

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
        return array_merge(
            parent::buildBaseQueryParams(),
            isset($this->eyes) ? ['eyes' => $this->eyes] : [],
            isset($this->mouth) ? ['mouth' => $this->mouth] : [],
        );
    }
}
