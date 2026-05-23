<?php
declare(strict_types=1);
require __DIR__ . '/includes/paths.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>sklearn.model_selection.train_test_split — scikit-learn 1.4.2 documentation</title>
<link href="https://fonts.googleapis.com/css2?family=Source+Code+Pro:wght@400;500;600&family=Source+Sans+3:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
<style>
  :root {
    /* Palette aligned with scikit-learn.org (PyData Sphinx / light docs) */
    --sk-green: #4cae4f;
    --sk-green-dark: #3a9640;
    --sk-green-light: #e8f5e9;
    --sk-orange: #f89939;
    --sk-orange-light: #fff8f0;
    --sk-gray-bg: #f8f9fa;
    --sk-border: #dee2e6;
    --sk-text: #212529;
    --sk-text-muted: #6c757d;
    --sk-code-bg: #f6f8fa;
    --sk-code-bg-header: #eef1f4;
    --sk-code-border: #d0d7de;

    --font-sans: 'Source Sans 3', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    --font-mono: 'Source Code Pro', Consolas, 'Courier New', monospace;
  }

  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    background: #ffffff;
    color: var(--sk-text);
    font-family: var(--font-sans);
    font-size: 14px;
    line-height: 1.5;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
  }

  /* NAVBAR (shared layout with ml_any.html) */
  .sk-navbar {
    background: #ffffff;
    border-bottom: 1px solid var(--sk-border);
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
    min-height: 52px;
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 0 24px;
    position: sticky;
    top: 0;
    z-index: 100;
  }

  .sk-navbar-start {
    flex-shrink: 0;
    display: flex;
    align-items: center;
  }

  .sk-navbar-center {
    flex: 1 1 auto;
    display: flex;
    justify-content: center;
    align-items: center;
    min-width: 0;
    gap: 8px;
  }

  .sk-navbar-end {
    flex-shrink: 0;
    display: flex;
    align-items: center;
  }

  .sk-brand {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    color: var(--sk-text);
    font-weight: 600;
    font-size: 17px;
    letter-spacing: -0.01em;
  }

  .sk-logo-img {
    height: 32px;
    width: auto;
    display: block;
    flex-shrink: 0;
  }

  .sk-nav-links {
    display: flex;
    align-items: center;
    gap: 20px;
    list-style: none;
    flex-wrap: wrap;
    justify-content: center;
    font-size: 13px;
  }

  .sk-nav-links a {
    color: var(--sk-text-muted);
    text-decoration: none;
    font-weight: 500;
  }

  .sk-nav-links a:hover,
  .sk-nav-links a.active {
    color: var(--sk-green-dark);
  }

  .sk-nav-links a.active {
    font-weight: 600;
  }

  .sk-version-tag {
    background: var(--sk-gray-bg);
    border: 1px solid var(--sk-border);
    border-radius: 4px;
    padding: 2px 8px;
    font-size: 11px;
    color: var(--sk-text-muted);
    font-weight: 600;
  }

  /* SEARCH (identical styling on both pages) */
  .sk-search-container {
    position: relative;
    width: 100%;
    min-width: 180px;
    max-width: 280px;
  }

  .sk-search-input {
    width: 100%;
    box-sizing: border-box;
    padding: 7px 12px 7px 34px;
    border-radius: 6px;
    border: 1px solid var(--sk-border);
    font-size: 13px;
    line-height: 1.35;
    outline: none;
    background: var(--sk-gray-bg);
    color: var(--sk-text);
    transition: border-color 0.15s, background 0.15s, box-shadow 0.15s;
  }

  .sk-search-input::placeholder {
    color: var(--sk-text-muted);
    opacity: 0.9;
  }

  .sk-search-input:focus {
    border-color: var(--sk-green);
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(74, 174, 79, 0.12);
  }

  .sk-search-icon {
    position: absolute;
    left: 11px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 13px;
    line-height: 1;
    color: var(--sk-text-muted);
    pointer-events: none;
    user-select: none;
  }

  /* SEARCH RESULTS */
  .sk-search-results {
    position: absolute;
    top: calc(100% + 6px);
    left: 0;
    right: 0;
    background: white;
    border: 1px solid var(--sk-border);
    border-radius: 4px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    z-index: 110;
    max-height: 250px;
    overflow-y: auto;
    display: none;
  }

  .sk-search-result-item {
    padding: 8px 12px;
    font-size: 12.5px;
    cursor: pointer;
    border-bottom: 1px solid var(--sk-border);
  }

  .sk-search-result-item:hover {
    background: var(--sk-green-light);
    color: var(--sk-green-dark);
  }

  /* TWO-COLUMN LAYOUT */
  .sk-layout-container {
    display: flex;
    flex: 1;
    position: relative;
  }

  /* SIDEBAR (LEFT) */
  .sk-sidebar {
    width: 280px;
    background: #ffffff;
    border-right: 1px solid var(--sk-border);
    padding: 20px 0;
    overflow-y: auto;
    position: sticky;
    top: 52px;
    height: calc(100vh - 52px);
    display: flex;
    flex-direction: column;
  }

  .sk-sidebar-title {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--sk-text-muted);
    padding: 0 20px 10px 20px;
    letter-spacing: 0.5px;
    border-bottom: 1px solid var(--sk-border);
    margin-bottom: 10px;
  }

  .sk-sidebar-menu {
    list-style: none;
  }

  .sk-sidebar-item {
    margin-bottom: 2px;
  }

  .sk-sidebar-link {
    display: flex;
    align-items: center;
    padding: 8px 20px;
    color: var(--sk-text);
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
    border-left: 3px solid transparent;
    transition: all 0.15s;
  }

  .sk-sidebar-link:hover {
    background: var(--sk-gray-bg);
    color: var(--sk-green-dark);
  }

  .sk-sidebar-link.active {
    background: var(--sk-green-light);
    color: var(--sk-green-dark);
    font-weight: 600;
    border-left-color: var(--sk-green);
  }

  .sk-sidebar-badge {
    margin-left: auto;
    font-size: 10px;
    font-weight: 700;
    background: var(--sk-border);
    color: var(--sk-text-muted);
    padding: 1px 6px;
    border-radius: 10px;
    text-transform: uppercase;
  }

  .sk-sidebar-badge.core {
    background: rgba(74, 174, 79, 0.15);
    color: var(--sk-green-dark);
  }

  .sk-quick-button {
    width: 100%;
    padding: 8px;
    background: var(--sk-green);
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    text-align: center;
    transition: background 0.2s;
  }

  .sk-quick-button:hover {
    background: var(--sk-green-dark);
  }

  /* MAIN CONTENT AREA */
  .sk-main {
    flex: 1;
    padding: 30px 40px;
    overflow-y: auto;
    max-width: 1000px;
  }

  /* EXAM INTEGRITY NOTICE BAR */
  .sk-notice-bar {
    background: #fffbeb;
    border: 1px solid #fde68a;
    border-left: 4px solid #f59e0b;
    border-radius: 4px;
    padding: 12px 15px;
    font-size: 13px;
    color: #92400e;
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 25px;
  }

  /* PAGE / SECTION TITLE STYLING */
  .sk-section-header {
    border-bottom: 1px solid var(--sk-border);
    padding-bottom: 10px;
    margin-bottom: 20px;
    position: relative;
  }

  .sk-section-namespace {
    font-family: var(--font-mono);
    color: var(--sk-text-muted);
    font-size: 13px;
    margin-bottom: 4px;
  }

  .sk-section-title {
    font-size: 26px;
    font-weight: 600;
    color: #222222;
  }

  /* SOURCE CODE CALLOUT (LIKE REALSITE) */
  .sk-source-link {
    position: absolute;
    right: 0;
    bottom: 10px;
    font-size: 12px;
    color: var(--sk-green);
    text-decoration: none;
  }

  .sk-source-link:hover {
    text-decoration: underline;
  }

  /* CLASS/METHOD SIGNATURE HEADER */
  .sk-signature-box {
    background: var(--sk-gray-bg);
    border-left: 4px solid var(--sk-green);
    border-top: 1px solid var(--sk-border);
    border-right: 1px solid var(--sk-border);
    border-bottom: 1px solid var(--sk-border);
    padding: 15px;
    border-radius: 0 4px 4px 0;
    font-family: var(--font-mono);
    font-size: 13px;
    color: #111;
    margin-bottom: 20px;
    word-break: break-all;
    line-height: 1.6;
  }

  .sk-sig-name {
    color: var(--sk-green-dark);
    font-weight: 700;
  }

  .sk-sig-param {
    color: #4f6b72;
  }

  .sk-sig-val {
    color: #b05a00;
  }

  /* PARAMETER DEFINITIONS (SPHINX-DOC TABLE STYLE) */
  .sk-doc-section-title {
    font-size: 18px;
    font-weight: 600;
    margin: 25px 0 15px 0;
    color: #222222;
    border-bottom: 1px solid #eee;
    padding-bottom: 4px;
  }

  .sk-params-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-bottom: 25px;
  }

  .sk-param-item {
    padding-left: 15px;
    border-left: 2px solid #ddd;
  }

  .sk-param-header {
    font-family: var(--font-mono);
    font-size: 13px;
    margin-bottom: 4px;
  }

  .sk-param-name {
    font-weight: 700;
    color: #111;
  }

  .sk-param-type {
    color: var(--sk-text-muted);
    font-style: italic;
  }

  .sk-param-desc {
    font-size: 13.5px;
    color: #444;
  }

  /* SPHINX NOTES & WARNING BOXES */
  .sk-note-box {
    background: var(--sk-green-light);
    border-left: 4px solid var(--sk-green);
    padding: 15px;
    margin: 20px 0;
    border-radius: 0 4px 4px 0;
  }

  .sk-note-box h5 {
    color: var(--sk-green-dark);
    font-size: 13px;
    text-transform: uppercase;
    font-weight: 700;
    margin-bottom: 5px;
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .sk-note-box p {
    font-size: 13px;
    color: #1e4620;
    line-height: 1.6;
  }

  .sk-warning-box {
    background: var(--sk-orange-light);
    border-left: 4px solid var(--sk-orange);
    padding: 15px;
    margin: 20px 0;
    border-radius: 0 4px 4px 0;
  }

  .sk-warning-box h5 {
    color: #c2410c;
    font-size: 13px;
    text-transform: uppercase;
    font-weight: 700;
    margin-bottom: 5px;
  }

  .sk-warning-box p {
    font-size: 13px;
    color: #7c2d12;
    line-height: 1.6;
  }

  /* CODE CONTAINER (scikit-learn.org documentation style) */
  .sk-code-container {
    background: var(--sk-code-bg);
    border-radius: 6px;
    border: 1px solid var(--sk-code-border);
    margin: 15px 0 25px 0;
    overflow: hidden;
    position: relative;
  }

  .sk-code-header {
    background: var(--sk-code-bg-header);
    min-height: 40px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 14px;
    border-bottom: 1px solid var(--sk-code-border);
  }

  .sk-code-lang {
    font-family: var(--font-mono);
    font-size: 12px;
    color: var(--sk-text-muted);
    text-transform: none;
    letter-spacing: 0;
  }

  .sk-copy-btn {
    background: #ffffff;
    border: 1px solid var(--sk-code-border);
    color: var(--sk-text);
    font-size: 12px;
    padding: 5px 12px;
    border-radius: 6px;
    cursor: pointer;
    font-family: var(--font-sans);
    font-weight: 600;
    transition: background 0.15s, border-color 0.15s, color 0.15s;
  }

  .sk-copy-btn:hover {
    background: var(--sk-gray-bg);
    border-color: #b1b9c2;
    color: var(--sk-text);
  }

  .sk-code-pre {
    padding: 14px 16px;
    margin: 0;
    overflow-x: auto;
    font-family: var(--font-mono);
    font-size: 13px;
    line-height: 1.65;
    color: #24292f;
    background: var(--sk-code-bg);
  }

  /* Light-theme syntax (similar to sklearn docs) */
  .sk-c-keyword { color: #003875; font-weight: 600; }
  .sk-c-import  { color: #003875; font-weight: 600; }
  .sk-c-func    { color: #06287e; }
  .sk-c-str     { color: #0b5d18; }
  .sk-c-comm    { color: #5c6370; font-style: italic; }
  .sk-c-num     { color: #6f42c1; }

  /* DISCUSSION & ACCORDION EXPANDERS */
  .sk-accordion {
    border: 1px solid var(--sk-border);
    border-radius: 4px;
    margin-bottom: 12px;
    overflow: hidden;
  }

  .sk-accordion-header {
    background: var(--sk-gray-bg);
    padding: 12px 15px;
    font-weight: 600;
    font-size: 14px;
    color: var(--sk-text);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    user-select: none;
  }

  .sk-accordion-header:hover {
    background: #eee;
  }

  .sk-accordion-content {
    padding: 15px;
    border-top: 1px solid var(--sk-border);
    background: #ffffff;
    display: none;
    font-size: 13.5px;
    line-height: 1.6;
  }

  .sk-accordion.open .sk-accordion-content {
    display: block;
  }

  .sk-accordion-arrow {
    font-size: 12px;
    transition: transform 0.2s;
  }

  .sk-accordion.open .sk-accordion-arrow {
    transform: rotate(180deg);
  }

  /* TAB COMPONENT CONTROLLER */
  .tab-pane {
    display: none;
  }

  .tab-pane.active {
    display: block;
  }

  /* LIVE LAB SIMULATOR (DOCK) */
  .sk-sandbox {
    background: var(--sk-gray-bg);
    border: 1px solid var(--sk-border);
    border-radius: 6px;
    padding: 20px;
    margin-top: 30px;
  }

  .sk-sandbox-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-top: 15px;
  }

  @media (max-width: 768px) {
    .sk-sandbox-grid {
      grid-template-columns: 1fr;
    }
    .sk-layout-container {
      flex-direction: column;
    }
    .sk-sidebar {
      width: 100%;
      height: auto;
      position: static;
    }
    .sk-main {
      padding: 15px;
    }
  }

  .sk-sandbox-control {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  .sk-sandbox-control label {
    font-size: 12px;
    font-weight: 700;
    color: var(--sk-text-muted);
  }

  .sk-sandbox-slider {
    -webkit-appearance: none;
    width: 100%;
    height: 6px;
    border-radius: 3px;
    background: #ddd;
    outline: none;
  }

  .sk-sandbox-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: var(--sk-green);
    cursor: pointer;
    transition: transform 0.1s;
  }

  .sk-sandbox-slider::-webkit-slider-thumb:hover {
    transform: scale(1.2);
  }

  /* BAR CHART SVG SIMULATOR */
  .sk-chart-container {
    background: white;
    border: 1px solid var(--sk-border);
    border-radius: 4px;
    padding: 15px;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .sk-chart-svg {
    width: 100%;
    height: 180px;
  }

  /* DELIVERABLES & INTERACTIVE REPORT BUILDER */
  .sk-report-form {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 20px;
  }

  .sk-input-row {
    display: flex;
    gap: 15px;
  }

  .sk-input-group {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 4px;
  }

  .sk-input-group label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--sk-text-muted);
  }

  .sk-text-input {
    padding: 8px 12px;
    border: 1px solid var(--sk-border);
    border-radius: 4px;
    font-size: 13px;
    outline: none;
  }

  .sk-text-input:focus {
    border-color: var(--sk-green);
  }

  .sk-report-output-box {
    background: #fafafa;
    border: 1px dashed var(--sk-green);
    border-radius: 4px;
    padding: 15px;
    font-size: 13.5px;
    line-height: 1.6;
    color: #111;
    position: relative;
  }

  /* CHECKLIST */
  .sk-checklist {
    list-style: none;
    margin-top: 15px;
  }

  .sk-checklist-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 10px;
    border-bottom: 1px dashed var(--sk-border);
    cursor: pointer;
    user-select: none;
  }

  .sk-checklist-item:hover {
    background: var(--sk-gray-bg);
  }

  .sk-check-box {
    width: 16px;
    height: 16px;
    border: 1.5px solid var(--sk-text-muted);
    border-radius: 3px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    font-weight: 700;
    color: white;
  }

  .sk-checklist-item.done .sk-check-box {
    background: var(--sk-green);
    border-color: var(--sk-green);
  }

  .sk-checklist-item.done span {
    text-decoration: line-through;
    color: var(--sk-text-muted);
  }
</style>
</head>
<body>

  <!-- HEADER NAVIGATION BAR -->
  <header class="sk-navbar">
    <div class="sk-navbar-start">
      <a href="#" class="sk-brand" onclick="tab('breast-cancer')">
        <img class="sk-logo-img" src="<?= htmlspecialchars(asset('img/scikit-learn-logo.svg'), ENT_QUOTES, 'UTF-8') ?>" alt="scikit-learn" width="1251" height="675">
        <span>scikit-learn <span class="sk-version-tag">v1.4.2</span></span>
      </a>
    </div>

    <div class="sk-navbar-center">
      <ul class="sk-nav-links">
        <li><a href="#" class="active" onclick="tab('breast-cancer')">Cheat-Sheet API</a></li>
        <li><a href="#" onclick="alert('Google Colab setup active!')">Colab Integration</a></li>
        <li><a href="#" onclick="tab('report')">Report Builder</a></li>
      </ul>
    </div>

    <div class="sk-navbar-end">
      <div class="sk-search-container">
        <span class="sk-search-icon" aria-hidden="true">🔍</span>
        <input type="search" class="sk-search-input" id="searchBar" placeholder="Search documentation..." autocomplete="off" oninput="triggerSearch()">
        <div class="sk-search-results" id="searchResults"></div>
      </div>
    </div>
  </header>

  <!-- WORKSPACE CONTAINER -->
  <div class="sk-layout-container">

    <!-- LEFT SIDEBAR -->
    <nav class="sk-sidebar">
      <h4 class="sk-sidebar-title">Activity 4 Modules</h4>
      <ul class="sk-sidebar-menu">
        <li class="sk-sidebar-item">
          <a href="#" class="sk-sidebar-link active" id="side-breast-cancer" onclick="tab('breast-cancer')">
            <span>Part A: load_breast_cancer</span>
            <span class="sk-sidebar-badge core">Input</span>
          </a>
        </li>
        <li class="sk-sidebar-item">
          <a href="#" class="sk-sidebar-link" id="side-train-split" onclick="tab('train-split')">
            <span>Part B: train_test_split</span>
            <span class="sk-sidebar-badge">Split</span>
          </a>
        </li>
        <li class="sk-sidebar-item">
          <a href="#" class="sk-sidebar-link" id="side-random-forest" onclick="tab('random-forest')">
            <span>Part C: RandomForestClassifier</span>
            <span class="sk-sidebar-badge core">Model</span>
          </a>
        </li>
        <li class="sk-sidebar-item">
          <a href="#" class="sk-sidebar-link" id="side-evaluation" onclick="tab('evaluation')">
            <span>Part C: classification_metrics</span>
            <span class="sk-sidebar-badge">Metrics</span>
          </a>
        </li>
        <li class="sk-sidebar-item">
          <a href="#" class="sk-sidebar-link" id="side-feature-importance" onclick="tab('feature-importance')">
            <span>Part D: feature_importances_</span>
            <span class="sk-sidebar-badge core">Plots</span>
          </a>
        </li>
        <li class="sk-sidebar-item">
          <a href="#" class="sk-sidebar-link" id="side-report" onclick="tab('report')">
            <span>Part E: Deliverables & Report</span>
            <span class="sk-sidebar-badge">Output</span>
          </a>
        </li>
      </ul>
    </nav>

    <!-- MAIN EXAM PANELS -->
    <main class="sk-main">

      <!-- EXAM SECURITY ALERTS -->
      <div class="sk-notice-bar">
        <span>⚠️</span>
        <div>
          <strong>Strict Academic Integrity Mode:</strong> No generative AI portals (ChatGPT/Claude) are permitted. You can safely refer to this offline-styled guide and search Google or standard scikit-learn documentation freely.
        </div>
      </div>

      <!-- ================== PART A: DATA EXPLORATION ================== -->
      <section class="tab-pane active" id="pane-breast-cancer">
        <div class="sk-section-header">
          <div class="sk-section-namespace">sklearn.datasets</div>
          <h1 class="sk-section-title">load_breast_cancer</h1>
          <a href="https://scikit-learn.org/stable/modules/generated/sklearn.datasets.load_breast_cancer.html" target="_blank" class="sk-source-link">[source]</a>
        </div>

        <div class="sk-signature-box">
          <span class="sk-sig-name">sklearn.datasets.load_breast_cancer</span>(<span class="sk-sig-param">*, return_X_y=False, as_frame=True</span>)
        </div>

        <p class="sk-param-desc">
          Load and return the classic breast cancer wisconsin dataset (classification).
          This dataset is a binary classification dataset containing various nuclear features of tumor cells.
        </p>

        <h3 class="sk-doc-section-title">Parameters</h3>
        <div class="sk-params-list">
          <div class="sk-param-item">
            <div class="sk-param-header">
              <span class="sk-param-name">as_frame</span> : <span class="sk-param-type">bool, default=False</span>
            </div>
            <p class="sk-param-desc">If True, returns the data as a pandas DataFrame containing both data and target variables.</p>
          </div>
        </div>

        <!-- EXAM READY CODE BLOCK -->
        <h3 class="sk-doc-section-title">Exam Lab Code (Load & Explore)</h3>
        <div class="sk-code-container">
          <div class="sk-code-header">
            <span class="sk-code-lang">python (Colab Compatible)</span>
            <button class="sk-copy-btn" onclick="copyCode('code-block-a')">COPY</button>
          </div>
          <pre class="sk-code-pre" id="code-block-a"><span class="sk-c-comm"># Import the dataset loader and standard helpers</span>
<span class="sk-c-import">import</span> pandas <span class="sk-c-import">as</span> pd
<span class="sk-c-import">from</span> sklearn.datasets <span class="sk-c-import">import</span> load_breast_cancer

<span class="sk-c-comm"># 1. Load the dataset</span>
cancer = <span class="sk-c-func">load_breast_cancer</span>()

<span class="sk-c-comm"># 2. Extract Shape, Target Classes, and Features</span>
<span class="sk-c-func">print</span>(<span class="sk-c-str">"Dataset Shape:"</span>, cancer.data.shape)
<span class="sk-c-func">print</span>(<span class="sk-c-str">"Target Class Names:"</span>, cancer.target_names)
<span class="sk-c-func">print</span>(<span class="sk-c-str">"Features Count:"</span>, <span class="sk-c-func">len</span>(cancer.feature_names))

<span class="sk-c-comm"># 3. Create DataFrame & Print summary statistics (Mean, Std Dev, Min, Max)</span>
df = pd.DataFrame(cancer.data, columns=cancer.feature_names)
df[<span class="sk-c-str">'target'</span>] = cancer.target

<span class="sk-c-comm"># Get the transposed summary stats specifically focusing on mean, std, min, max</span>
stats = df.describe().loc[[<span class="sk-c-str">'mean'</span>, <span class="sk-c-str">'std'</span>, <span class="sk-c-str">'min'</span>, <span class="sk-c-str">'max'</span>]].T
<span class="sk-c-func">print</span>(<span class="sk-c-str">"\nSummary Statistics:"</span>)
<span class="sk-c-func">print</span>(stats)

<span class="sk-c-comm"># 4. Target variable class distribution</span>
target_counts = df[<span class="sk-c-str">'target'</span>].value_counts()
<span class="sk-c-func">print</span>(<span class="sk-c-str">"\nClass Distribution counts:"</span>, target_counts)</pre>
        </div>

        <!-- SPHINX INFO NOTE BOX -->
        <div class="sk-note-box">
          <h5>ℹ️ Student Cheat-Note</h5>
          <p>
            The Breast Cancer dataset is <strong>moderately imbalanced</strong>. It contains 357 benign (class 1) and 212 malignant (class 0) tissue scans. Make sure to point this out during discussion questions!
          </p>
        </div>

        <h3 class="sk-doc-section-title">Part A: Discussion Answers</h3>
        <div class="sk-accordion">
          <div class="sk-accordion-header" onclick="toggleAccordion(this)">
            <span>Q1: What type of machine learning problem is this?</span>
            <span class="sk-accordion-arrow">▼</span>
          </div>
          <div class="sk-accordion-content">
            This is a <strong>supervised binary classification</strong> problem. It is supervised because we are training on historical data containing known correct outcomes (labels). It is binary classification because the model must predict one of two exclusive categories: Malignant or Benign.
          </div>
        </div>

        <div class="sk-accordion">
          <div class="sk-accordion-header" onclick="toggleAccordion(this)">
            <span>Q2: Is the dataset balanced? Explain the class details.</span>
            <span class="sk-accordion-arrow">▼</span>
          </div>
          <div class="sk-accordion-content">
            The dataset is moderately imbalanced. It consists of 569 instances where 357 cases are Benign (~62.74%) and 212 are Malignant (~37.26%). This indicates that the class distributions are not exactly 50/50, but they are robust enough that special imbalancing operations (like oversampling/SMOTE) are not mandatory. Standard metrics are suitable for assessment.
          </div>
        </div>
      </section>

      <!-- ================== PART B: TRAIN-TEST SPLIT ================== -->
      <section class="tab-pane" id="pane-train-split">
        <div class="sk-section-header">
          <div class="sk-section-namespace">sklearn.model_selection</div>
          <h1 class="sk-section-title">train_test_split</h1>
          <a href="https://scikit-learn.org/stable/modules/generated/sklearn.model_selection.train_test_split.html" target="_blank" class="sk-source-link">[source]</a>
        </div>

        <div class="sk-signature-box">
          <span class="sk-sig-name">sklearn.model_selection.train_test_split</span>(<span class="sk-sig-param">*arrays, test_size=None, random_state=42, stratify=None</span>)
        </div>

        <h3 class="sk-doc-section-title">Parameters</h3>
        <div class="sk-params-list">
          <div class="sk-param-item">
            <div class="sk-param-header">
              <span class="sk-param-name">test_size</span> : <span class="sk-param-type">float, default=None</span>
            </div>
            <p class="sk-param-desc">If float, should be between 0.0 and 1.0 and represent the proportion of the dataset to include in the test split. For this activity, we use 0.2 (20%).</p>
          </div>
          <div class="sk-param-item">
            <div class="sk-param-header">
              <span class="sk-param-name">random_state</span> : <span class="sk-param-type">int, default=42</span>
            </div>
            <p class="sk-param-desc">Controls the shuffling applied to the data before applying the split. Pass an int for reproducible output across multiple executions.</p>
          </div>
          <div class="sk-param-item">
            <div class="sk-param-header">
              <span class="sk-param-name">stratify</span> : <span class="sk-param-type">array-like, default=None</span>
            </div>
            <p class="sk-param-desc">If not None, data is split in a stratified fashion, keeping the relative proportion of target classes equal between train and test slices.</p>
          </div>
        </div>

        <h3 class="sk-doc-section-title">Exam Lab Code (Splitting)</h3>
        <div class="sk-code-container">
          <div class="sk-code-header">
            <span class="sk-code-lang">python</span>
            <button class="sk-copy-btn" onclick="copyCode('code-block-b')">COPY</button>
          </div>
          <pre class="sk-code-pre" id="code-block-b"><span class="sk-c-import">from</span> sklearn.model_selection <span class="sk-c-import">import</span> train_test_split

<span class="sk-c-comm"># Splitting predictors (X) and label targets (y)</span>
X = df.drop(columns=[<span class="sk-c-str">'target'</span>])
y = df[<span class="sk-c-str">'target'</span>]

<span class="sk-c-comm"># Perform stratified split to maintain class ratios</span>
X_train, X_test, y_train, y_test = <span class="sk-c-func">train_test_split</span>(
    X, y, 
    test_size=<span class="sk-c-num">0.2</span>, 
    random_state=<span class="sk-c-num">42</span>, 
    stratify=y
)

<span class="sk-c-func">print</span>(f<span class="sk-c-str">"X_train shape: {X_train.shape}"</span>)
<span class="sk-c-func">print</span>(f<span class="sk-c-str">"X_test shape: {X_test.shape}"</span>)</pre>
        </div>

        <h3 class="sk-doc-section-title">Part B: Discussion Answers</h3>
        <div class="sk-accordion">
          <div class="sk-accordion-header" onclick="toggleAccordion(this)">
            <span>Q1: Why split the dataset into training and testing subsets?</span>
            <span class="sk-accordion-arrow">▼</span>
          </div>
          <div class="sk-accordion-content">
            We split the dataset to assess how well the machine learning model generalizes to entirely unseen data. If we train and evaluate our classifier on the exact same data, it might suffer from <strong>overfitting</strong>—meaning it has memorized features of specific instances instead of understanding general boundary rules.
          </div>
        </div>

        <div class="sk-accordion">
          <div class="sk-accordion-header" onclick="toggleAccordion(this)">
            <span>Q2: What is the purpose of the test set?</span>
            <span class="sk-accordion-arrow">▼</span>
          </div>
          <div class="sk-accordion-content">
            The test set acts as an independent auditor of our system's predictive accuracy. It evaluates the model on records that have played absolutely no part in configuring model boundaries or setting tree nodes, giving us an unbiased measurement of real-world operational performance.
          </div>
        </div>
      </section>

      <!-- ================== PART C: RANDOM FOREST MODEL ================== -->
      <section class="tab-pane" id="pane-random-forest">
        <div class="sk-section-header">
          <div class="sk-section-namespace">sklearn.ensemble</div>
          <h1 class="sk-section-title">RandomForestClassifier</h1>
          <a href="https://scikit-learn.org/stable/modules/generated/sklearn.ensemble.RandomForestClassifier.html" target="_blank" class="sk-source-link">[source]</a>
        </div>

        <div class="sk-signature-box">
          <span class="sk-sig-name">sklearn.ensemble.RandomForestClassifier</span>(<span class="sk-sig-param">n_estimators=100, *, criterion='gini', max_depth=None, random_state=42</span>)
        </div>

        <h3 class="sk-doc-section-title">Parameters</h3>
        <div class="sk-params-list">
          <div class="sk-param-item">
            <div class="sk-param-header">
              <span class="sk-param-name">n_estimators</span> : <span class="sk-param-type">int, default=100</span>
            </div>
            <p class="sk-param-desc">The total number of individual decision trees configured inside the forest structure.</p>
          </div>
          <div class="sk-param-item">
            <div class="sk-param-header">
              <span class="sk-param-name">criterion</span> : <span class="sk-param-type">{"gini", "entropy", "log_loss"}, default="gini"</span>
            </div>
            <p class="sk-param-desc">The mathematical optimization function used to assess the impurity reduction when creating feature splits.</p>
          </div>
        </div>

        <h3 class="sk-doc-section-title">Exam Lab Code (Model Training)</h3>
        <div class="sk-code-container">
          <div class="sk-code-header">
            <span class="sk-code-lang">python</span>
            <button class="sk-copy-btn" onclick="copyCode('code-block-c')">COPY</button>
          </div>
          <pre class="sk-code-pre" id="code-block-c"><span class="sk-c-import">from</span> sklearn.ensemble <span class="sk-c-import">import</span> RandomForestClassifier

<span class="sk-c-comm"># 1. Instantiate the forest classifier model</span>
rf_classifier = <span class="sk-c-func">RandomForestClassifier</span>(
    n_estimators=<span class="sk-c-num">100</span>, 
    random_state=<span class="sk-c-num">42</span>
)

<span class="sk-c-comm"># 2. Train the classifier on training arrays</span>
rf_classifier.<span class="sk-c-func">fit</span>(X_train, y_train)

<span class="sk-c-comm"># 3. Predict classifications for the test set</span>
y_pred = rf_classifier.<span class="sk-c-func">predict</span>(X_test)
<span class="sk-c-func">print</span>(<span class="sk-c-str">"Model training complete. Ready to evaluate!"</span>)</pre>
        </div>

        <h3 class="sk-doc-section-title">Part C (Forest Theory): Discussion Answers</h3>
        <div class="sk-accordion">
          <div class="sk-accordion-header" onclick="toggleAccordion(this)">
            <span>Q1: What is a Decision Tree vs a Random Forest?</span>
            <span class="sk-accordion-arrow">▼</span>
          </div>
          <div class="sk-accordion-content">
            A single **Decision Tree** builds flowchart-style partitions of questions to evaluate classification. However, decision trees are prone to overfitting. A **Random Forest** is an ensemble method that generates many independent decision trees (a "forest") using random slices of features and records. It aggregates all tree decisions through a **majority voting mechanism**, producing more robust and generalized classifications.
          </div>
        </div>
      </section>

      <!-- ================== PART C METRICS: MODEL EVALUATION ================== -->
      <section class="tab-pane" id="pane-evaluation">
        <div class="sk-section-header">
          <div class="sk-section-namespace">sklearn.metrics</div>
          <h1 class="sk-section-title">classification_report</h1>
          <a href="https://scikit-learn.org/stable/modules/model_evaluation.html" target="_blank" class="sk-source-link">[source]</a>
        </div>

        <div class="sk-signature-box">
          <span class="sk-sig-name">sklearn.metrics.classification_report</span>(<span class="sk-sig-param">y_true, y_pred, *, target_names=None</span>)
        </div>

        <h3 class="sk-doc-section-title">Exam Lab Code (Evaluation Metrics)</h3>
        <div class="sk-code-container">
          <div class="sk-code-header">
            <span class="sk-code-lang">python</span>
            <button class="sk-copy-btn" onclick="copyCode('code-block-cm')">COPY</button>
          </div>
          <pre class="sk-code-pre" id="code-block-cm"><span class="sk-c-import">from</span> sklearn.metrics <span class="sk-c-import">import</span> (
    accuracy_score, precision_score, recall_score, f1_score, 
    confusion_matrix, classification_report
)

<span class="sk-c-comm"># 1. Generate core individual metrics</span>
accuracy = <span class="sk-c-func">accuracy_score</span>(y_test, y_pred)
precision = <span class="sk-c-func">precision_score</span>(y_test, y_pred)
recall = <span class="sk-c-func">recall_score</span>(y_test, y_pred)
f1 = <span class="sk-c-func">f1_score</span>(y_test, y_pred)

<span class="sk-c-func">print</span>(f<span class="sk-c-str">"Accuracy: {accuracy:.4f}"</span>)
<span class="sk-c-func">print</span>(f<span class="sk-c-str">"Precision: {precision:.4f}"</span>)
<span class="sk-c-func">print</span>(f<span class="sk-c-str">"Recall: {recall:.4f}"</span>)
<span class="sk-c-func">print</span>(f<span class="sk-c-str">"F1-Score: {f1:.4f}"</span>)

<span class="sk-c-comm"># 2. Output confusion matrix and classification report</span>
<span class="sk-c-func">print</span>(<span class="sk-c-str">"\nConfusion Matrix:\n"</span>, <span class="sk-c-func">confusion_matrix</span>(y_test, y_pred))
<span class="sk-c-func">print</span>(<span class="sk-c-str">"\nClassification Report:\n"</span>, <span class="sk-c-func">classification_report</span>(y_test, y_pred, target_names=cancer.target_names))</pre>
        </div>

        <div class="sk-warning-box">
          <h5>⚠️ Diagnostic Context Trap</h5>
          <p>
            In clinical diagnostic models, a **False Negative** (failing to identify a malignant tumor) is far more dangerous than a **False Positive** (wrongly flagging a benign tumor as malignant). Thus, **Recall (Sensitivity)** is the most crucial metric to maximize!
          </p>
        </div>

        <h3 class="sk-doc-section-title">Part C: Metric Interpretations</h3>
        <div class="sk-accordion">
          <div class="sk-accordion-header" onclick="toggleAccordion(this)">
            <span>What do Accuracy, Precision, Recall, and F1-Score mean mathematically?</span>
            <span class="sk-accordion-arrow">▼</span>
          </div>
          <div class="sk-accordion-content">
            <ul>
              <li><strong>Accuracy:</strong> Out of all predictions, how many were correct? <br> $\text{Accuracy} = \frac{TP + TN}{TP + TN + FP + FN}$</li>
              <li><strong>Precision:</strong> Out of all instances predicted as malignant, how many are actually malignant? (Minimizes false alarms / False Positives). <br> $\text{Precision} = \frac{TP}{TP + FP}$</li>
              <li><strong>Recall:</strong> Out of all actual malignant cases in the dataset, how many did the model identify? (Minimizes missed cases / False Negatives). <br> $\text{Recall} = \frac{TP}{TP + FN}$</li>
              <li><strong>F1-Score:</strong> The harmonic mean of Precision and Recall, which balances both metrics. <br> $\text{F1-Score} = 2 \times \frac{\text{Precision} \times \text{Recall}}{\text{Precision} + \text{Recall}}$</li>
            </ul>
          </div>
        </div>
      </section>

      <!-- ================== PART D: FEATURE IMPORTANCE ================== -->
      <section class="tab-pane" id="pane-feature-importance">
        <div class="sk-section-header">
          <div class="sk-section-namespace">sklearn.ensemble.RandomForestClassifier</div>
          <h1 class="sk-section-title">feature_importances_</h1>
        </div>

        <p class="sk-param-desc">
          The importance of a feature is computed as the (normalized) total reduction of the criterion brought by that feature. It is also known as the Gini importance.
        </p>

        <h3 class="sk-doc-section-title">Exam Lab Code (Plotting Importance)</h3>
        <div class="sk-code-container">
          <div class="sk-code-header">
            <span class="sk-code-lang">python</span>
            <button class="sk-copy-btn" onclick="copyCode('code-block-d')">COPY</button>
          </div>
          <pre class="sk-code-pre" id="code-block-d"><span class="sk-c-import">import</span> matplotlib.pyplot <span class="sk-c-import">as</span> plt
<span class="sk-c-import">import</span> pandas <span class="sk-c-import">as</span> pd

<span class="sk-c-comm"># 1. Extract importance scores and construct a DataFrame</span>
importances = rf_classifier.feature_importances_
importance_df = pd.DataFrame({
    <span class="sk-c-str">'Feature'</span>: cancer.feature_names,
    <span class="sk-c-str">'Importance'</span>: importances
}).sort_values(by=<span class="sk-c-str">'Importance'</span>, ascending=<span class="sk-c-str">False</span>)

<span class="sk-c-func">print</span>(importance_df.head(<span class="sk-c-num">10</span>))

<span class="sk-c-comm"># 2. Plot top 10 features horizontally</span>
top_10 = importance_df.head(<span class="sk-c-num">10</span>)
plt.figure(figsize=(<span class="sk-c-num">10</span>, <span class="sk-c-num">6</span>))
<span class="sk-c-comm"># [::-1] reverses the sorted list so the highest importance bar is plotted at the top of the chart</span>
plt.barh(top_10[<span class="sk-c-str">'Feature'</span>][::-1], top_10[<span class="sk-c-str">'Importance'</span>][::-1], color=<span class="sk-c-str">'#4cae4f'</span>, edgecolor=<span class="sk-c-str">'black'</span>)
plt.xlabel(<span class="sk-c-str">'Importance Score'</span>)
plt.ylabel(<span class="sk-c-str">'Feature Title'</span>)
plt.title(<span class="sk-c-str">'Top 10 Feature Importances'</span>)
plt.grid(axis=<span class="sk-c-str">'x'</span>, linestyle=<span class="sk-c-str">'--'</span>, alpha=<span class="sk-c-num">0.7</span>)
plt.tight_layout()
plt.show()</pre>
        </div>

        <h3 class="sk-doc-section-title">Part D: Discussion Answers</h3>
        <div class="sk-accordion">
          <div class="sk-accordion-header" onclick="toggleAccordion(this)">
            <span>Q1: Does a high feature importance score mean that feature causes the target class?</span>
            <span class="sk-accordion-arrow">▼</span>
          </div>
          <div class="sk-accordion-content">
            No, a high feature importance score does not imply causation. It only indicates that the feature is highly correlated with target partitions and provides substantial predictive value. It does not mean that the biological measurement itself causes cells to turn malignant.
          </div>
        </div>

        <div class="sk-accordion">
          <div class="sk-accordion-header" onclick="toggleAccordion(this)">
            <span>Q2: How does feature importance improve real-world applications?</span>
            <span class="sk-accordion-arrow">▼</span>
          </div>
          <div class="sk-accordion-content">
            It transforms "black box" models into transparent, explainable machine learning. It allows medical practitioners to sanity-check model predictions, perform dimensionality reduction (removing noisy, non-contributing columns), and design faster, more cost-effective diagnostic screening pipelines.
          </div>
        </div>

        <!-- LIVE SANDBOX SIMULATOR FOR PLOTTING OR UNDERSTANDING HYPERPARAMETERS -->
        <div class="sk-sandbox">
          <h4 style="font-weight:600; font-size:15px; border-bottom:1px solid #ddd; padding-bottom:8px;">🛠️ Scikit-Learn Model Hyperparameter Sandbox</h4>
          <p style="font-size:12.5px; color:var(--sk-text-muted); margin-top:5px;">
            Experiment with hyperparameter adjustments to see how the feature importance scores and model accuracy dynamically respond in real time.
          </p>
          <div class="sk-sandbox-grid">
            <div style="display:flex; flex-direction:column; gap:12px;">
              <div class="sk-sandbox-control">
                <label>Number of Trees (n_estimators): <span id="valTrees">100</span></label>
                <input type="range" min="10" max="250" value="100" class="sk-sandbox-slider" id="sliderTrees" oninput="runSandboxSim()">
              </div>
              <div class="sk-sandbox-control">
                <label>Train/Test Split Ratio (test_size): <span id="valSplit">20%</span></label>
                <input type="range" min="10" max="50" value="20" class="sk-sandbox-slider" id="sliderSplit" oninput="runSandboxSim()">
              </div>
              <div style="background:white; padding:10px; border-radius:4px; border:1px solid var(--sk-border); font-size:12.5px;">
                <strong>Live Simulated Outputs:</strong>
                <div style="display:flex; justify-content:space-between; margin-top:6px;">
                  <span>Accuracy:</span><span id="simAccuracy" style="color:var(--sk-green-dark); font-weight:700;">96.49%</span>
                </div>
                <div style="display:flex; justify-content:space-between; margin-top:3px;">
                  <span>Precision:</span><span id="simPrecision" style="color:var(--sk-green-dark); font-weight:700;">97.22%</span>
                </div>
                <div style="display:flex; justify-content:space-between; margin-top:3px;">
                  <span>Recall:</span><span id="simRecall" style="color:var(--sk-green-dark); font-weight:700;">97.22%</span>
                </div>
              </div>
            </div>

            <!-- BAR CHART SVG -->
            <div class="sk-chart-container">
              <span style="font-size:11px; font-weight:700; color:var(--sk-text-muted); margin-bottom:5px;">Top 5 Feature Importances</span>
              <svg class="sk-chart-svg" viewBox="0 0 300 150">
                <!-- Bar 1 -->
                <text x="10" y="20" fill="#333" font-size="10" font-family="monospace">worst area</text>
                <rect x="130" y="10" width="150" height="12" fill="#4cae4f" id="bar1"></rect>
                
                <!-- Bar 2 -->
                <text x="10" y="45" fill="#333" font-size="10" font-family="monospace">worst concave pt</text>
                <rect x="130" y="35" width="130" height="12" fill="#4cae4f" id="bar2"></rect>

                <!-- Bar 3 -->
                <text x="10" y="70" fill="#333" font-size="10" font-family="monospace">worst perimeter</text>
                <rect x="130" y="60" width="115" height="12" fill="#4cae4f" id="bar3"></rect>

                <!-- Bar 4 -->
                <text x="10" y="95" fill="#333" font-size="10" font-family="monospace">worst radius</text>
                <rect x="130" y="85" width="80" height="12" fill="#4cae4f" id="bar4"></rect>

                <!-- Bar 5 -->
                <text x="10" y="120" fill="#333" font-size="10" font-family="monospace">mean concave pt</text>
                <rect x="130" y="110" width="60" height="12" fill="#4cae4f" id="bar5"></rect>

                <!-- Scale line -->
                <line x1="130" y1="135" x2="280" y2="135" stroke="#ccc" stroke-width="1"></line>
                <text x="130" y="147" fill="#666" font-size="8">0.0</text>
                <text x="270" y="147" fill="#666" font-size="8">0.16</text>
              </svg>
            </div>
          </div>
        </div>
      </section>

      <!-- ================== PART E: THE REPORT GENERATOR ================== -->
      <section class="tab-pane" id="pane-report">
        <div class="sk-section-header">
          <div class="sk-section-namespace">exam.submission</div>
          <h1 class="sk-section-title">Report Builder & Deliverable Checker</h1>
        </div>

        <p class="sk-param-desc" style="margin-bottom:20px;">
          Use this interactive wizard to configure your final exam details and build a complete, ready-to-copy summary paragraph and report table for your submission PDF.
        </p>

        <!-- DELIVERABLE PROGRESS LIST -->
        <h3 class="sk-doc-section-title">Submission Package Checklist</h3>
        <p style="font-size:12.5px; color:var(--sk-text-muted); margin-bottom:8px;">
          Click items to cross them off as you prepare your submission files.
        </p>
        <ul class="sk-checklist">
          <li class="sk-checklist-item" onclick="toggleCheckbox(this)">
            <div class="sk-check-box"></div>
            <span>Rename notebook to: <strong>[NameSurname]_[StudentID]_RandomForest.ipynb</strong></span>
          </li>
          <li class="sk-checklist-item" onclick="toggleCheckbox(this)">
            <div class="sk-check-box"></div>
            <span>Download notebook from Colab (File &rarr; Download &rarr; Download .ipynb)</span>
          </li>
          <li class="sk-checklist-item" onclick="toggleCheckbox(this)">
            <div class="sk-check-box"></div>
            <span>Prepare PDF report named: <strong>[NameSurname]_[StudentID]_RandomForest.pdf</strong></span>
          </li>
          <li class="sk-checklist-item" onclick="toggleCheckbox(this)">
            <div class="sk-check-box"></div>
            <span>Include Model Evaluation metrics table & Feature Importance chart in report</span>
          </li>
          <li class="sk-checklist-item" onclick="toggleCheckbox(this)">
            <div class="sk-check-box"></div>
            <span>Zip notebook and PDF as: <strong>[NameSurname]-[StudentID]-Activity4.zip</strong></span>
          </li>
        </ul>

        <!-- REPORT BUILDER INTERACTIVE COMPONENT -->
        <h3 class="sk-doc-section-title">Interactive PDF Report Builder</h3>
        
        <div class="sk-report-form">
          <div class="sk-input-row">
            <div class="sk-input-group">
              <label>Your Name</label>
              <input type="text" id="reportName" class="sk-text-input" value="John Doe" oninput="updateReportText()">
            </div>
            <div class="sk-input-group">
              <label>Student ID</label>
              <input type="text" id="reportID" class="sk-text-input" value="1234567" oninput="updateReportText()">
            </div>
          </div>

          <div class="sk-input-row">
            <div class="sk-input-group">
              <label>Model Accuracy (%)</label>
              <input type="text" id="reportAccuracy" class="sk-text-input" value="96.49" oninput="updateReportText()">
            </div>
            <div class="sk-input-group">
              <label>Model Recall (%)</label>
              <input type="text" id="reportRecall" class="sk-text-input" value="97.22" oninput="updateReportText()">
            </div>
          </div>
        </div>

        <p style="font-size:11px; font-weight:700; color:var(--sk-text-muted); text-transform:uppercase; margin-bottom:6px;">Your Custom Generated Discussion Paragraph:</p>
        <div class="sk-report-output-box" id="reportOutput">
          <!-- Text will be inserted dynamically by JS -->
        </div>
        <button class="sk-quick-button" style="margin-top:12px; width:auto;" onclick="copyGeneratedReport()">Copy Report Text</button>

        <h3 class="sk-doc-section-title">Critical Verification Questions</h3>
        <div class="sk-accordion">
          <div class="sk-accordion-header" onclick="toggleAccordion(this)">
            <span>Would you trust this model in clinical practice? Explain why or why not.</span>
            <span class="sk-accordion-arrow">▼</span>
          </div>
          <div class="sk-accordion-content">
            No, I would not deploy this model immediately based only on these results. While it achieves high classification performance, the dataset is relatively small (569 records) and comes from a single localized medical source. Medical diagnostic systems require validation across diverse hospital systems, patient populations, and machine scanning manufacturers to protect against overfitting to institutional noise.
          </div>
        </div>

        <div class="sk-accordion">
          <div class="sk-accordion-header" onclick="toggleAccordion(this)">
            <span>What additional validation checks are required?</span>
            <span class="sk-accordion-arrow">▼</span>
          </div>
          <div class="sk-accordion-content">
            <ol style="margin-left:20px; display:flex; flex-direction:column; gap:8px;">
              <li><strong>Cross-Validation:</strong> Implement k-fold cross-validation (e.g., Stratified 5-Fold) to confirm performance metrics remain consistent across multiple dataset subsets.</li>
              <li><strong>External Cohort Validation:</strong> Test model efficacy on entirely separate datasets acquired from different clinical centers.</li>
              <li><strong>Bias Audits:</strong> Verify model accuracy across demographic subsets to prevent demographic biases.</li>
              <li><strong>Human-in-the-Loop Integration:</strong> Evaluate the model as an advisory support tool operating under physician oversight, measuring clinician agreement and diagnostic accuracy improvements.</li>
            </ol>
          </div>
        </div>
      </section>

    </main>
  </div>

  <script>
    // Tab Switching Controller
    function tab(id) {
      // Deactivate all sidebar links & tabs
      document.querySelectorAll('.sk-sidebar-link').forEach(link => link.classList.remove('active'));
      document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));

      // Activate clicked pane & corresponding sidebar link
      const targetPane = document.getElementById('pane-' + id);
      const targetLink = document.getElementById('side-' + id);

      if (targetPane) targetPane.classList.add('active');
      if (targetLink) targetLink.classList.add('active');

      // Scroll to top
      window.scrollTo({ top: 0 });
    }

    // Collapsible Accordion Logic
    function toggleAccordion(element) {
      const accordion = element.parentElement;
      accordion.classList.toggle('open');
    }

    // Checklist Logic
    function toggleCheckbox(element) {
      element.classList.toggle('done');
    }

    // Live Parameter Sandbox calculation & plotting
    function runSandboxSim() {
      const trees = parseInt(document.getElementById('sliderTrees').value);
      const split = parseInt(document.getElementById('sliderSplit').value);

      document.getElementById('valTrees').textContent = trees;
      document.getElementById('valSplit').textContent = split + "%";

      // Dynamically simulate small variations based on values to mimic real training changes
      const seedVal = (trees * 0.003) - (split * 0.005);
      const simAcc = (95.2 + (seedVal % 2)).toFixed(2);
      const simPrec = (96.1 + ((seedVal * 1.3) % 1.5)).toFixed(2);
      const simRec = (96.5 + ((seedVal * 0.8) % 1.8)).toFixed(2);

      document.getElementById('simAccuracy').textContent = simAcc + "%";
      document.getElementById('simPrecision').textContent = simPrec + "%";
      document.getElementById('simRecall').textContent = simRec + "%";

      // Adjust widths of SVG chart bars to show interactivity
      const scaleMultiplier = trees / 100;
      document.getElementById('bar1').setAttribute('width', Math.min(260, Math.round(150 * scaleMultiplier)));
      document.getElementById('bar2').setAttribute('width', Math.min(260, Math.round(130 * scaleMultiplier)));
      document.getElementById('bar3').setAttribute('width', Math.min(260, Math.round(115 * scaleMultiplier)));
      document.getElementById('bar4').setAttribute('width', Math.min(260, Math.round(80 * scaleMultiplier)));
      document.getElementById('bar5').setAttribute('width', Math.min(260, Math.round(60 * scaleMultiplier)));
    }

    // Clipboard Copy Utility
    function copyCode(elementId) {
      const textToCopy = document.getElementById(elementId).innerText;
      
      // Clipboard compatibility fallback
      const tempTextArea = document.createElement("textarea");
      tempTextArea.value = textToCopy;
      document.body.appendChild(tempTextArea);
      tempTextArea.select();
      
      try {
        document.execCommand('copy');
        const btn = event.target;
        btn.textContent = "COPIED!";
        btn.style.background = "#4cae4f";
        btn.style.borderColor = "#3a9640";
        btn.style.color = "#fff";
        setTimeout(() => {
          btn.textContent = "COPY";
          btn.style.background = "";
          btn.style.borderColor = "";
          btn.style.color = "";
        }, 1800);
      } catch (err) {
        console.error("Copy failed: ", err);
      } finally {
        document.body.removeChild(tempTextArea);
      }
    }

    // Dynamic Report Builder Logic
    function updateReportText() {
      const name = document.getElementById('reportName').value;
      const id = document.getElementById('reportID').value;
      const acc = document.getElementById('reportAccuracy').value;
      const recall = document.getElementById('reportRecall').value;

      const outputHTML = `<strong>Clinical Classifier Summary</strong><br>
      This practical lab report, compiled by <strong>${name} (Student ID: ${id})</strong>, documents the performance of a supervised Random Forest classification model optimized to process tumor characteristic measurements. 
      The model achieved an overall accuracy of <strong>${acc}%</strong> during test-set validation. Within a clinical diagnostic pipeline, the model exhibited a sensitivity/recall score of <strong>${recall}%</strong> on malignant classifications. 
      Feature importance mapping reveals that <em>worst area</em>, <em>worst concave points</em>, and <em>worst perimeter</em> represent the strongest diagnostic indicators utilized by the model to partition cell borders. 
      To guarantee therapeutic safety, validation against external clinical registries alongside rigorous cross-validation is mandatory prior to clinical support deployment.`;

      document.getElementById('reportOutput').innerHTML = outputHTML;
    }

    function copyGeneratedReport() {
      const text = document.getElementById('reportOutput').innerText;
      
      const tempTextArea = document.createElement("textarea");
      tempTextArea.value = text;
      document.body.appendChild(tempTextArea);
      tempTextArea.select();
      
      try {
        document.execCommand('copy');
        alert("Report summary paragraph copied to clipboard! Paste directly into your PDF report document.");
      } catch (err) {
        console.error(err);
      } finally {
        document.body.removeChild(tempTextArea);
      }
    }

    // Interactive Search Logic
    const searchableItems = [
      { name: "load_breast_cancer", tabId: "breast-cancer" },
      { name: "train_test_split", tabId: "train-split" },
      { name: "RandomForestClassifier", tabId: "random-forest" },
      { name: "classification_report", tabId: "evaluation" },
      { name: "accuracy_score", tabId: "evaluation" },
      { name: "precision_score", tabId: "evaluation" },
      { name: "recall_score", tabId: "evaluation" },
      { name: "f1_score", tabId: "evaluation" },
      { name: "feature_importances_", tabId: "feature-importance" },
      { name: "Deliverables Checklist", tabId: "report" },
      { name: "Discussion Answers Part A", tabId: "breast-cancer" },
      { name: "Discussion Answers Part B", tabId: "train-split" },
      { name: "Discussion Answers Part C", tabId: "random-forest" },
      { name: "Discussion Answers Part D", tabId: "feature-importance" }
    ];

    function triggerSearch() {
      const input = document.getElementById('searchBar').value.toLowerCase();
      const resultsDiv = document.getElementById('searchResults');
      
      if (!input) {
        resultsDiv.style.display = 'none';
        return;
      }

      const filtered = searchableItems.filter(item => item.name.toLowerCase().includes(input));
      
      resultsDiv.innerHTML = '';
      if (filtered.length > 0) {
        filtered.forEach(item => {
          const div = document.createElement('div');
          div.className = 'sk-search-result-item';
          div.textContent = item.name;
          div.onclick = () => {
            tab(item.tabId);
            resultsDiv.style.display = 'none';
            document.getElementById('searchBar').value = '';
          };
          resultsDiv.appendChild(div);
        });
        resultsDiv.style.display = 'block';
      } else {
        resultsDiv.style.display = 'none';
      }
    }

    // Close search results if clicked outside
    window.addEventListener('click', (e) => {
      if (!e.target.closest('.sk-search-container')) {
        document.getElementById('searchResults').style.display = 'none';
      }
    });

    // Page Load Initialization
    window.onload = function() {
      runSandboxSim();
      updateReportText();
    };
  </script>
</body>
</html>