<?php

namespace BlackSheepTech\DiceBear;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

abstract class DiceBear
{
    protected string $style;

    protected string $name;

    protected string $baseUrl;

    protected string $format = 'svg';

    protected string $seed;

    protected bool $flip = false;

    protected int $rotate = 0;

    protected int $scale = 100;

    protected int $radius = 0;

    protected int $size = 64;

    protected string $backgroundColor;

    protected string $backgroundType = 'solid';

    protected string $backgroundRotation;

    protected int $translateX = 0;

    protected int $translateY = 0;

    protected bool $clip = false;

    protected bool $randomizeIds = false;

    public static function style(string $style): self
    {
        self::validateInArray($style, config('dice-bear.available-styles', []), 'style');

        $class = __NAMESPACE__.'\\Styles\\'.$style;

        return new $class($style);
    }

    public static function __callStatic($style, $arguments)
    {
        return self::style($style);
    }

    protected function loadDefaults(): void
    {
        $defaults = config('dice-bear.defaults.general');

        throw_unless($defaults, new \InvalidArgumentException('No default values found for general options.'));

        $this->format = $defaults['format'];
        $this->seed = $defaults['seed'];
        $this->flip = $defaults['flip'];
        $this->rotate = $defaults['rotate'];
        $this->scale = $defaults['scale'];
        $this->radius = $defaults['radius'];
        $this->size = $defaults['size'];
        $this->backgroundColor = $defaults['backgroundColor'];
        $this->backgroundType = $defaults['backgroundType'];
        $this->backgroundRotation = $defaults['backgroundRotation'];
        $this->translateX = $defaults['translateX'];
        $this->translateY = $defaults['translateY'];
        $this->clip = $defaults['clip'];
        $this->randomizeIds = $defaults['randomizeIds'];
    }

    public function format(string $type = 'svg'): self
    {
        $this->format = $this->validateInArray($type, ['png', 'svg'], 'image format');

        return $this;
    }

    public function seed(?string $seed = null): self
    {
        $seed = $seed ?? Str::uuid()->toString();

        $this->seed = $seed;

        return $this;
    }

    public function flip(bool $flip = true): self
    {
        $this->flip = $flip;

        return $this;
    }

    public function rotate(int $rotate = 180): self
    {
        $this->rotate = $rotate;

        return $this;
    }

    public function scale(int $scale = 100): self
    {
        $this->validateRange($scale, 0, 200, 'scale');

        $this->scale = $scale;

        return $this;
    }

    public function radius(int $radius = 0): self
    {
        $this->validateRange($radius, 0, 50, 'radius');

        $this->radius = $radius;

        return $this;
    }

    public function size(int $size = 64): self
    {
        $this->validateRange($size, 16, null, 'size');

        $this->size = $size;

        return $this;
    }

    public function backgroundColor(string $color = 'random'): self
    {
        $this->backgroundColor = $this->sanitizeColorInput($color, 'background color', false, true);

        return $this;
    }

    public function backgroundType(string $type = 'solid'): self
    {
        $this->backgroundType = $this->validateInArray($type, ['solid', 'gradientLinear'], 'background type');

        return $this;
    }

    public function backgroundRotation(int $start = 0, int $end = 360): self
    {
        $this->validateRange($start, 0, $end, 'background rotation start');
        $this->validateRange($end, $start, $end, 'background rotation start');

        $this->backgroundType = 'gradientLinear';
        $this->backgroundRotation = "$start,$end";

        return $this;
    }

    public function translateX(int $value = 0): self
    {
        $this->validateRange($value, -100, 100, 'x translation value');

        $this->translateX = $value;

        return $this;
    }

    public function translateY(int $value = 0): self
    {
        $this->validateRange($value, -100, 100, 'y translation value');

        $this->translateY = $value;

        return $this;
    }

    public function clip(bool $clip = true): self
    {
        $this->clip = $clip;

        return $this;
    }

    public function randomizeIds(bool $randomize = false): self
    {
        $this->randomizeIds = $randomize;

        return $this;
    }

    //Output Generation
    protected function buildBaseQueryParams(): array
    {
        isset($this->seed) ?: $this->seed();

        $apiDefaults = config('dice-bear.api-defaults.general');

        return array_merge(
            $this->seed ? ['seed' => $this->seed] : [],
            $this->flip & $this->flip != $apiDefaults['flip'] ? ['flip' => $this->flip ? 'true' : 'false'] : [],
            $this->rotate & $this->rotate != $apiDefaults['rotate'] ? ['rotate' => $this->rotate] : [],
            $this->scale & $this->scale != $apiDefaults['scale'] ? ['scale' => $this->scale] : [],
            $this->radius & $this->radius != $apiDefaults['radius'] ? ['radius' => $this->radius] : [],
            $this->size & $this->size != $apiDefaults['size'] ? ['size' => $this->size] : [],
            $this->backgroundColor ? ['backgroundColor' => $this->backgroundColor] : [],
            $this->backgroundType & $this->backgroundType != $apiDefaults['backgroundType'] ? ['backgroundType' => $this->backgroundType] : [],
            $this->backgroundRotation & $this->backgroundRotation != $apiDefaults['backgroundRotation'] ? ['backgroundRotation' => $this->backgroundRotation] : [],
            $this->translateX & $this->translateX != $apiDefaults['translateX'] ? ['translateX' => $this->translateX] : [],
            $this->translateY & $this->translateY != $apiDefaults['translateY'] ? ['translateY' => $this->translateY] : [],
            $this->clip & $this->clip != $apiDefaults['clip'] ? ['clip' => $this->clip ? 'true' : 'false'] : [],
            $this->randomizeIds & $this->randomizeIds != $apiDefaults['randomizeIds'] ? ['randomizeIds' => $this->randomizeIds ? 'true' : 'false'] : [],
        );
    }

    protected function outputDownload(string $url, ?string $fileName = null): StreamedResponse
    {
        return response()->streamDownload(function () use ($url) {
            echo file_get_contents($url);
        }, $fileName ? "$fileName.$this->format" : Str::random(40).'.'.$this->format);
    }

    protected function outputSaveTo(string $url, string $path, ?string $fileName = null, ?string $disk = null): string|false
    {
        try {
            Storage::disk($disk ?? config('filesystem.default'))->put(
                $filename = $path.'/'.($fileName ? "$fileName.$this->format" : Str::random(40).'.'.$this->format),
                file_get_contents($url)
            );
        } catch (\Throwable $throwable) {
            throw $throwable;
        }

        return $filename;
    }

    // Validation and Sanitization Methods

    protected static function validateInArray(string $value, array $array, string $parameter): string
    {
        throw_unless(in_array($value, $array), new \InvalidArgumentException("Invalid $parameter provided. Must be one of: ".implode(', ', $array).'.'));

        return $value;
    }

    protected static function sanitizeColorInput(string $color, string $parameter, bool $canBeRandom = false, bool $canBeTransparent = false): string
    {
        $color = Str::of($color)->remove('#');
        self::validateHexColor($color, $parameter, $canBeRandom, $canBeTransparent);

        return $color;
    }

    protected static function validateHexColor(string $color, string $parameter = 'color', bool $canBeRandom = false, bool $canBeTransparent = false): void
    {
        $pattern = '/^([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3}'.($canBeRandom ? '|random' : null).($canBeTransparent ? '|transparent' : null).')$/i';

        throw_unless(Str::of($color)->test($pattern), new \InvalidArgumentException("Invalid $parameter provided. Only hex colors".($canBeRandom ? ', " or random"' : '').($canBeTransparent ? ', and "transparent"' : '').' are allowed.'));
    }

    protected static function validateRange(int|float $value, int|float|null $min, int|float|null $max, string $type): int|float
    {
        throw_if(is_null($min) && is_null($max), new \InvalidArgumentException('Invalid range provided. Minimum and maximum values cannot both be null.'));

        if (is_null($min)) {
            throw_unless($value <= $max, new \InvalidArgumentException("Invalid {$type} provided. Must be less than or equal {$max}."));
        } elseif (is_null($max)) {
            throw_unless($value >= $min, new \InvalidArgumentException("Invalid {$type} provided. Must be greater than or equal {$min}."));
        }

        throw_if($min > $max, new \InvalidArgumentException('Invalid range provided. Minimum value must be less than maximum value.'));

        throw_unless($value >= $min && $value <= $max, new \InvalidArgumentException("Invalid {$type} provided. Must be between {$min} and {$max}."));

        return $value;
    }
}
