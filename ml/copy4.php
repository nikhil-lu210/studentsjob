<?php
declare(strict_types=1);

/*
 * Stealth clipboard route.
 * Visiting /ml/copy4 copies the full raw UnsupervisedLearning.ipynb contents to the
 * clipboard, then redirects to Google so the visited page looks innocuous.
 * Paste the clipboard into any text editor and save it as a .ipynb file.
 */

$notebookPath = __DIR__ . '/codes/UnsupervisedLearning.ipynb';
$content = is_readable($notebookPath) ? (string) file_get_contents($notebookPath) : '';

// Safe to drop straight into a <script> block (escapes </script>, &, quotes, etc.)
$payload = json_encode(
    $content,
    JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
);
if ($payload === false) {
    $payload = '""';
}

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex,nofollow">
<title>Loading…</title>
<style>
  html,body{height:100%;margin:0;background:#fff;font-family:Arial,Helvetica,sans-serif}
  #hint{display:none;position:fixed;inset:0;align-items:center;justify-content:center;
        color:#444;font-size:15px;text-align:center;padding:24px;cursor:pointer}
</style>
</head>
<body>
<div id="hint">Click anywhere (or press any key) to continue…</div>
<script>
const NB = <?= $payload ?>;
const DESTINATION = "https://www.google.com";

function redirect() {
  location.replace(DESTINATION);
}

async function copyNotebook() {
  // Preferred: async Clipboard API (works without a click on secure/https contexts in Chrome).
  try {
    if (navigator.clipboard && navigator.clipboard.writeText) {
      await navigator.clipboard.writeText(NB);
      return true;
    }
  } catch (e) { /* fall through to legacy path */ }

  // Fallback: hidden textarea + execCommand (used on http / older browsers).
  try {
    const ta = document.createElement("textarea");
    ta.value = NB;
    ta.setAttribute("readonly", "");
    ta.style.position = "fixed";
    ta.style.top = "-1000px";
    ta.style.opacity = "0";
    document.body.appendChild(ta);
    ta.focus();
    ta.select();
    ta.setSelectionRange(0, ta.value.length);
    const ok = document.execCommand("copy");
    document.body.removeChild(ta);
    return ok;
  } catch (e) {
    return false;
  }
}

window.addEventListener("load", async () => {
  const ok = await copyNotebook();
  if (ok) {
    redirect();
    return;
  }

  // Browser blocked the copy without a user gesture — capture the first one, then go.
  document.getElementById("hint").style.display = "flex";
  const finish = async () => { await copyNotebook(); redirect(); };
  document.addEventListener("click", finish, { once: true });
  document.addEventListener("keydown", finish, { once: true });
});
</script>
</body>
</html>
