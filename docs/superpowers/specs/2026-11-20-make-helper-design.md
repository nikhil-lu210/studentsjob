# Custom `make:helper` Design

**Date:** 2026-11-20  
**Status:** Approved

## Problem

`browner12/helpers` does not support Laravel 13 (Illuminate constraints stop at ^12). We need `php artisan make:helper HelperFolder/HelperName` without that package.

## Approach

App-native Artisan generator + recursive helper autoload (option 3).

## Design

1. **Command:** `app/Console/Commands/MakeHelperCommand.php`  
   - Signature: `make:helper {name}`  
   - Writes under `app/Helpers/`  
   - Supports nested paths (`HelperFolder/HelperName` → `app/Helpers/HelperFolder/HelperName.php`)

2. **Stub:** procedural PHP with `function_exists` guard (not a class)

3. **Autoload:** recursively `require_once` all `*.php` files under `app/Helpers/` from `AppServiceProvider::register()`

4. **Out of scope:** browner12 built-in package helpers; config file

## Success criteria

- `php artisan make:helper HelperFolder/HelperName` creates the file
- Functions defined in that file are available app-wide without manual require
