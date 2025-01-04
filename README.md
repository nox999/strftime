# strftime.php
Polyfill for the strftime function that was deprecated in PHP 8.1.0

The goal was to replace the `strftime` function with an equivalent that offers close to zero friction. The alternative offered by current versions of PHP is unnecessarily complicated and object-oriented. Date formatting should be simple and procedural.

## Usage
```
strftime_polyfill($format[,$timestamp,$locale]):string|false
```

## Parameters
**$format** - date format, more information here: https://www.php.net/manual/en/function.strftime.php

**$timestamp** - UNIX timestamp, `time()` if it's not defined

**$locale** - two letter string defining the language of the days of the week and months (e.g. `ro`), `en` if it's not defined

## Returns

Formated date/time or `false` if `$format` is not defined or empty.
