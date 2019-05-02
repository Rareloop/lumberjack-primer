# Primer for Lumberjack

## Installation

1. Add package using Composer
2. Add config file `{theme}/config/primer.php`
3. Add `{theme}/resources`
4. Remove `{theme}/views`
5. Update `{theme}/config/timber.php` (`['views']` => `['resources/views', 'resources/views/primer']`)
6. Add css/js assets `{theme}/assets/primer`
7. Update error handler with new template path
8. Update 404 controller with template
9. Add Alias for `Primer` facade in `{theme}/config/app.php`
