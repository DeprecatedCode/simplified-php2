# SimplifiedPHP Page Template
# @author Nate Ferrero

# Colors

bright:     '#fff'
normal:     '#f9fbfd'
tint:       '#ddd'
highlight:  '#6ad'
dark:       '#444'
purple:     '#a0a'
green:      '#0a0'

base: {@request.path.contains ? 'simplified-php': '/simplified-php/', *: '/'}

icon: {'<span class="typcn typcn-' i '"></span>'}

'<!doctype html>
<html>
<head>
  <title>SimplifiedPHP: ' title '</title>
  <link href="' base 'web/typicons.font/font/typicons.min.css" rel="stylesheet" type="text/css" />
  <style>
    body {
      font-family: "Open Sans", Tahoma, Arial, sans-serif;
      font-size: 15px;
      margin: 0;
      padding: 0 1.5em;
      color: #333;
    }
    
    a, .link {
      color: 'highlight';
      text-decoration: none;
      cursor: pointer;
    }
    
    a:hover, .link:hover {
      text-decoration: underline;
    }
    
    a:active, .link:active {
      opacity: 0.8;
    }
    
    blockquote {
      margin: 1em 0;
      padding: 0 0 0 1em;
    }

    h2 {
      font-size: 16px;
    }

    h3 {
      margin: 1em 0 0.25em;
      font-size: 11px;
    }

    p .typcn, h2 .typcn {
      vertical-align: 1px;
      font-size: 110%;
      margin-right: 0.2em;
      margin-left: 0.75em;
    }

    p .typcn:first-child, h2 .typcn:first-child {
      margin-left: 0;
    }
    
    table {
      margin: 1em 0;
      border-collapse: collapse;
    }
    
    table.centered th, table.centered td {
      text-align: center;
      vertical-align: middle;
      padding: 0.3em 0.5em;
    }

    td, th {
      background: rgba(0, 0, 0, 0.07);
      padding: 1em;
      border: 1px solid #bbb;
    }
    
    td *:first-child {
      margin-top: 0;
    }
    
    td *:last-child {
      margin-bottom: 0;
    }

    td {
      background: rgba(0, 0, 0, 0.02);
      vertical-align: top;
    }

    pre.code, .output {
      margin-top: 0;
      font-size: 13px;
      background: 'normal';
      border: 1px solid 'tint';
      padding: 12px;
      overflow-x: auto;
      margin-left: 5px;
    }

    pre.code {
      box-shadow: -5px 0 0 'purple';
    }

    .output {
      box-shadow: -5px 0 0 'green';
    }

    .output pre {
      margin: 0;
    }

    .output > *:first-child {
      margin-top: 0;
    }
    
    .output > *:last-child {
      margin-bottom: 0;
    }
    
    body code {
      background: transparent;
      padding: 0;
      font-size: 13px;
    }
    
    code {
      font-weight: normal;
      font-family: "Oxygen Mono", monospace;
    }
    
    .btn, .switch a {
      padding: 0.25em 0.75em;
      margin: 1em -1px 0 0;
      background: 'normal';
      text-decoration: none;
      color: inherit;
    }
    
    .bar {
      padding: 1.5em 1.5em 0;
      margin: 0 -1.5em 2em;
      background: 'normal';
      border-bottom: 2px solid 'highlight';
    }
    
    .bar *:first-child {
      margin-top: 0;
    }

    .btn {
      box-shadow: 0 2px 0 0 'highlight';
      border: 1px solid 'tint';
      border-bottom: none;
    }
    
    .btn.large {
      font-size: 120%;
      display: inline-block;
      margin: 0 -1px 0 0;
      padding: 0.5em 1.5em;
    }
    
    .btn:hover {
      background: 'bright';
      position: relative;
      text-decoration: none;
    }
    
    .btn:active {
      background: 'tint';
    }
    
    .btn.active {
      box-shadow: 0 4px 0 -2px 'bright', inset 0 1px 0 1px 'highlight', 0 0 0 2px 'highlight';
      background: #fff;
      border-color: 'highlight';
      color: #001;
      position: relative;
      z-index: 1;
    }
    
    .warning {
      border: 2px solid #b00;
      background: #f8f0f0 url(' base 'web/warning.png) no-repeat;
      background-position: 1em;
      background-size: 48px;
      color: #b00;
      padding: 1em 2em 1em 5em;
    }
    
    .switch {
      font-size: 70%;
      font-weight: bold;
      margin: 0.5em 0 1em;
      border: 2px solid 'dark';
      display: inline-block;
      padding: 3px 0 3px 0;
      overflow: hidden;
    }
    
    .switch a {
      border-left: 1px solid 'tint';
      margin-left: -2px;
    }
    
    .switch a:first-child {
      border-left: none;
      margin-left: 0;
    }
    
    .switch a:last-child {
      margin-right: -6px;
    }
    
    .switch a.selected {
      background: 'dark';
      color: 'bright';
    }
    
    .switch a.switch-code.selected {
      background: 'purple';
    }
    
    .switch a.switch-interlaced.selected {
      background-image:         linear-gradient(90deg, 'green' 49%, 'purple' 50%);
      background-image:      -o-linear-gradient(90deg, 'green' 49%, 'purple' 50%);
      background-image:    -moz-linear-gradient(90deg, 'green' 49%, 'purple' 50%);
      background-image: -webkit-linear-gradient(90deg, 'green' 49%, 'purple' 50%);
      background-image:     -ms-linear-gradient(90deg, 'green' 49%, 'purple' 50%);
    }
    
    .switch a.switch-result.selected {
      background: 'green';
    }
    
    .big-icon {
      position: absolute;
      top: 22px;
      font-size: 60px;
      right: 38px;
      font-weight: normal;
      color: #333;
    }
  </style>
  <link href="http://fonts.googleapis.com/css?family=Oxygen+Mono|Open+Sans:400,700" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/7.3/styles/github.min.css" />
  <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/7.3/highlight.min.js"></script>
  <script>window.hljs.initHighlightingOnLoad();</script>
</head>
<body>
  <a class="big-icon" href="https://github.com/NateFerrero/simplified-php">
    '(icon {i: 'social-github-circular'})'
  </a>
  <a class="big-icon" style="right: 104px; font-size: 45px; top: 32px" href="' base 'web/documentation/installation.php">
    '(icon {i: 'download-outline'})'
  </a>
  <div class="bar">
    <h1>SimplifiedPHP</h1>
'.print

@import "nav-buttons.php"

" </div><h1>" title "</h1>".print

@finally {'
</body>
</html>
'.print}