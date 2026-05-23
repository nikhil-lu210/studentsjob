# ML workbench (PHP)

Machine-learning lesson pages live under **`ml/`** so they are served under **`https://your-domain/ml`** (Apache document root must be the project folder **`studentsjob/`**, not **`ml/`**).

## Layout

| Path | Role |
|------|------|
| `index.html` | Site homepage at **`/`** |
| `ml/` | **`/ml`** app (Apache `DirectoryIndex` → `index.php`) |
| `ml/index.php` | Colab handbook — URL **`/ml`** |
| `ml/ml_any.php` | Universal workbench — URL **`/ml/ml_any`** |
| `ml/ml_test.php` | Cheat sheet page — URL **`/ml/ml_test`** |
| `ml/assets/css/` | Stylesheets |
| `ml/assets/img/` | Images (e.g. scikit-learn logo) |
| `ml/includes/paths.php` | `asset()` / `page_url()` helpers |

## Extensionless URLs (`/ml/ml_any` not `/ml/ml_any.php`)

`ml/.htaccess` enables `mod_rewrite` rules (with **`RewriteBase /ml/`**). If your site is deployed under another path segment, edit that directive to match.

**Apache** must load `mod_rewrite`, and **AllowOverride** for the vhost must include **FileInfo**.

## Laragon

1. Set the virtual host document root to **`...\studentsjob`** (the repo root — same folder as `index.html`).
2. Ensure `mod_rewrite` is enabled.
3. Restart Apache.
