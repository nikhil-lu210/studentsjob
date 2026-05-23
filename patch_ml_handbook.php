<?php
$root = __DIR__;
$html = file_get_contents("$root/ml.html");

$newStyle = '<link href="https://fonts.googleapis.com/css2?family=Source+Code+Pro:wght@400;500;600&family=Source+Sans+3:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">' . "\n"
    . '<link rel="stylesheet" href="ml_handbook_skin.css">' . "\n";

$patternStyle = '~<link href="https://fonts\.googleapis\.com/css2\?family=Fraunces[^>]+>\s*<style>[\s\S]*?</style>\s*~';

$html = preg_replace($patternStyle, $newStyle . "\n", $html, 1, $cnt);
if ($cnt !== 1) {
    fwrite(STDERR, "style replace failed: $cnt\n");
    exit(1);
}

$NEW_HEADER = <<<'HDR'
<body>

<div id="prog"></div>

<header class="sk-navbar">
  <div class="sk-navbar-start">
    <a href="#top" class="sk-brand">
      <img class="sk-logo-img" src="scikit-learn-logo.svg" alt="scikit-learn" width="1251" height="675">
      <span>scikit-learn <span class="sk-version-tag">ML Handbook</span></span>
    </a>
  </div>
  <div class="sk-navbar-center">
    <span class="sk-navbar-subtitle">Google Colab · Python · Beginner machine learning</span>
  </div>
  <div class="sk-navbar-end">
    <div class="sk-search-container">
      <span class="sk-search-icon" aria-hidden="true">🔍</span>
      <input type="search" class="sk-search-input" id="searchBar" placeholder="Jump to section…" autocomplete="off" oninput="triggerHandbookSearch()">
      <div class="sk-search-results" id="searchResults"></div>
    </div>
  </div>
</header>

<div class="sk-layout-container">
  <nav class="sk-sidebar" aria-label="Handbook sections">
    <h4 class="sk-sidebar-title">Handbook</h4>
    <ul class="sk-sidebar-menu">
      <li class="sk-sidebar-item"><a class="sk-sidebar-link" href="#top">Introduction</a></li>
      <li class="sk-sidebar-item"><a class="sk-sidebar-link" href="#open">Open Colab</a></li>
      <li class="sk-sidebar-item"><a class="sk-sidebar-link" href="#imports">Imports</a></li>
      <li class="sk-sidebar-item"><a class="sk-sidebar-link" href="#data">Load data</a></li>
      <li class="sk-sidebar-item"><a class="sk-sidebar-link" href="#tasks">ML tasks</a></li>
      <li class="sk-sidebar-item"><a class="sk-sidebar-link" href="#errors">Errors</a></li>
      <li class="sk-sidebar-item"><a class="sk-sidebar-link" href="#datasets">Datasets</a></li>
      <li class="sk-sidebar-item"><a class="sk-sidebar-link" href="#checklist">Checklist</a></li>
      <li class="sk-sidebar-item"><a class="sk-sidebar-link" href="#links">Resources</a></li>
    </ul>
  </nav>

  <main class="sk-main">

    <div id="top" class="handbook-intro w rv">
      <div class="sk-notice-bar">
        <span>🐍</span>
        <div><strong>No install required.</strong> Use this handbook beside a Colab tab: run cells top to bottom, substitute your file and column names, and use the sidebar or search to jump between sections.</div>
      </div>
      <div class="intro-head">
        <div class="badge">Beginner handbook</div>
        <h1 class="handbook-title">Your beginner's guide to <em>ML</em> on Google Colab</h1>
        <p class="handbook-lead">No experience needed. Open your browser, follow each step, and run your first machine learning model in minutes.</p>
      </div>
      <div class="cta-row">
        <a href="#open" class="btn btn-blue">Get started →</a>
        <a href="#checklist" class="btn btn-ghost">Quick checklist</a>
      </div>
      <div class="stats-row">
        <div class="stat"><div class="stat-n">8</div><div class="stat-l">Clear steps</div></div>
        <div class="vr"></div>
        <div class="stat"><div class="stat-n">6</div><div class="stat-l">Code templates</div></div>
        <div class="vr"></div>
        <div class="stat"><div class="stat-n">0</div><div class="stat-l">Prior experience</div></div>
        <div class="vr"></div>
        <div class="stat"><div class="stat-n">100%</div><div class="stat-l">Free</div></div>
      </div>
    </div>

    <hr class="sep">

HDR;

$patternHeader = '~<body>\s*<div id="prog"></div>\s*<!-- NAV -->[\s\S]*?<hr class="sep">\s*\n\s*\n~';

$html = preg_replace($patternHeader, $NEW_HEADER, $html, 1, $cnt);
if ($cnt !== 1) {
    fwrite(STDERR, "header replace failed: $cnt\n");
    exit(1);
}

if (strpos($html, '<!-- FOOTER -->') === false) {
    fwrite(STDERR, "footer marker missing\n");
    exit(1);
}
$html = str_replace(
    '<!-- FOOTER -->',
    "</main>\n\n</div><!-- sk-layout-container -->\n\n<!-- FOOTER -->",
    $html
);

$insertJs = <<<'JS'


  const handbookSearchItems = [
    { name: 'Introduction', id: 'top' },
    { name: 'Open Colab', id: 'open' },
    { name: 'Imports', id: 'imports' },
    { name: 'Load data', id: 'data' },
    { name: 'ML tasks', id: 'tasks' },
    { name: 'Errors', id: 'errors' },
    { name: 'Datasets', id: 'datasets' },
    { name: 'Checklist', id: 'checklist' },
    { name: 'Resources', id: 'links' },
  ];

  function triggerHandbookSearch() {
    const inputEl = document.getElementById('searchBar');
    const q = inputEl.value.toLowerCase().trim();
    const resultsDiv = document.getElementById('searchResults');
    if (!q) {
      resultsDiv.style.display = 'none';
      return;
    }
    const filtered = handbookSearchItems.filter(it => it.name.toLowerCase().includes(q));
    resultsDiv.innerHTML = '';
    filtered.forEach(item => {
      const div = document.createElement('div');
      div.className = 'sk-search-result-item';
      div.textContent = item.name;
      div.onclick = () => {
        const el = document.getElementById(item.id);
        if (el) el.scrollIntoView({ behavior: 'smooth' });
        resultsDiv.style.display = 'none';
        inputEl.value = '';
      };
      resultsDiv.appendChild(div);
    });
    resultsDiv.style.display = filtered.length ? 'block' : 'none';
  }

  window.addEventListener('click', (e) => {
    if (!e.target.closest('.sk-search-container')) {
      const r = document.getElementById('searchResults');
      if (r) r.style.display = 'none';
    }
  });

JS;

$anchor = "  // Progress bar";
if (strpos($html, $anchor) === false) {
    fwrite(STDERR, "progress anchor missing\n");
    exit(1);
}
$html = str_replace($anchor, $insertJs . "\n" . $anchor, $html);

file_put_contents("$root/ml.html", $html);
echo "ml.html patched\n";
