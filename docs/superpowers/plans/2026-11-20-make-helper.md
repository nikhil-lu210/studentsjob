# Custom make:helper Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add `php artisan make:helper` with recursive autoload of `app/Helpers/**/*.php`.

**Architecture:** One Artisan generator command + stub; load helpers in `AppServiceProvider::register()`.

**Tech Stack:** Laravel 13, PHP 8.3, Illuminate GeneratorCommand

---

### Task 1: Stub + MakeHelperCommand

**Files:**
- Create: `stubs/helper.stub`
- Create: `app/Console/Commands/MakeHelperCommand.php`

- [ ] Create stub with `function_exists` guard and placeholder function
- [ ] Create `MakeHelperCommand` extending `GeneratorCommand`, default namespace/dir `Helpers`, support nested names
- [ ] Verify: `php artisan make:helper HelperFolder/HelperName` creates `app/Helpers/HelperFolder/HelperName.php`

### Task 2: Autoload helpers

**Files:**
- Modify: `app/Providers/AppServiceProvider.php`

- [ ] In `register()`, recursively require all `app/Helpers/**/*.php`
- [ ] Verify: function from generated helper is callable in `php artisan tinker` or a one-liner
