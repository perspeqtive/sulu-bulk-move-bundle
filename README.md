# SuluBulkMoveBundle
![Packagist Version](https://img.shields.io/packagist/v/perspeqtive/sulu-bulk-move-bundle)

The **Sulu Bulk Move Bundle** moves all child pages from one Sulu navigation node to another.
Perfect for reorganizing whole parts of your page tree in one command.

---

## 🛠️ Installation
### Install the bundle via composer:

```bash
composer require perspeqtive/sulu-bulk-move-bundle
```

### Enable the bundle

Add the bundle to your config/bundles.php:

```php
return [
// ...
PERSPEQTIVE\SuluBulkMoveBundle\PERSPEQTIVESuluBulkMoveBundle::class => ['all' => true],
];
```

### Usage
Run the bulk move via Symfony command:

```bash
bin/console perspeqtive:sulu:bulk-move <source-uuid> <target-uuid> <locale>
```

| parameter    | description                                              |
|:-------------|:---------------------------------------------------------|
| source-uuid  | UUID of the parent page whose children you want to move  |
| target-uuid  | UUID of the target parent page                           |
| locale       | The page locale (e.g. de, en)                            |

Example:

```bash
bin/console perspeqtive:sulu:bulk-move 123e4567-e89b-12d3-a456-426614174000 123e4567-e89b-12d3-a456-426614174999 de
```

## 👩‍🍳 Contribution

Please feel free to fork and extend existing or add new features and send a pull request with your changes! To establish a consistent code quality, please provide unit tests for all your changes and adapt the documentation.