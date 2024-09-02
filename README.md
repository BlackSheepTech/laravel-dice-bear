<p align="center">
    <a href="https://github.com/BlackSheepTech/ui-avatars" target="_blank">
        <img src="https://avatars.githubusercontent.com/u/85756821?s=400&u=14843f72938dc40cbd14400f5b3daad45f054f43&v=4" width="200" alt="BlackSheepTech UiAvatars">
    </a>
</p>

<p align="center">
    <a href="https://packagist.org/packages/black-sheep-tech/dice-bear"><img src="https://img.shields.io/packagist/v/black-sheep-tech/dice-bear" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/black-sheep-tech/dice-bear"><img src="https://img.shields.io/packagist/dt/black-sheep-tech/dice-bear" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/black-sheep-tech/dice-bear"><img src="https://img.shields.io/packagist/l/black-sheep-tech/dice-bear" alt="License"></a>
    <a href="https://packagist.org/packages/black-sheep-tech/dice-bear"><img src="https://img.shields.io/github/stars/BlackSheepTech/dice-bear" alt="Stars"></a>
</p>

DiceBear is a PHP library for Laravel used to generate avatar using the DiceBear API (https://dicebear.com/).
This package provides a simple, fluent interface for customizing avatar parameters and generating the corresponding URL. It also allows downloading and saving the avatars locally. Support for self-hosted DiceBear API is also available.

## Installation

You can install the package via Composer:

```bash
composer require black-sheep-tech/dice-bear
```

## Usage

### Basic Usage (All Styles)

```php
use BlackSheepTech\DiceBear\DiceBear;

// Generates a random avatar using the 'botttsNeutral' style with a size of 128 pixels and 'Avesome Avatar Seeder' as seed.

$avatarUrl = DiceBear::style('botttsNeutral')->seed('Avesome Avatar Seeder')->size(128)->getUrl();

// Alternatively, you can use the following shorthand method:

$avatarUrl = DiceBear::botttsNeutral()->seed('Avesome Avatar Seeder')->size(128)->getUrl();

```

### Download Avatar

To download the avatar directly:

```php
// Prompts a download of the avatar to a file named 'john_doe_avatar.png', by default, if a file name is not provided, a random name will be generated.
use BlackSheepTech\DiceBear\DiceBear;

DiceBear::botttsNeutral()->seed('John Doe')->size(128)->download();
```

### Save Avatar Directly to Disk

To save the avatar to a specific location:

```php
use BlackSheepTech\DiceBear\DiceBear;

// Saves the avatar to 'avatars/john_doe_avatar.png' by default.
$avatarPath = DiceBear::botttsNeutral()->seed('John Doe')->size(128)->saveTo('avatars', 'john_doe_avatar');

// You can provided the disk to be used as the third parameter, by default, the application's default disk will be used.
$avatarPath = DiceBear::botttsNeutral()->seed('John Doe')->size(128)->saveTo('avatars', 'john_doe_avatar', 'public');
```

### Supported Styles

Currently, the following DiceBear styles are supported:

- [botttsNeutral](https://www.dicebear.com/styles/bottts-neutral/)
- [adventurerNeutral](https://www.dicebear.com/styles/adventurer-neutral/)
- [initials](https://www.dicebear.com/styles/initials/)

### Style Specific Options

Most styles have options specific to them.

- **BotttsNeutral** - [Detailed Options](botttsNeutralOptions.md)
  - Eyes
  - Mouth
- **AdventurerNeutral** - [Detailed Options](adventurerNeutralOptions.md)
  - Eyebrows
  - Eyes;
  - Mouth
  - Glasses
  - Glasses Probability
- **Initials** - Does not support any additional options

## Requirements

- PHP 8.0 or higher
- Laravel framework version 9.0 or higher

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request on GitHub.

## Credits

- [Israel Pinheiro](https://github.com/IsraelPinheiro)
- [All Contributors](https://github.com/BlackSheepTech/ui-avatars/graphs/contributors)
