# SimplifiedPHP Page Template
# @author Nate Ferrero

# Colors

bright: '#ffffff'
normal: '#f9fbfd'
dark:   '#ddd'
highlight: '#6ad'

'<!doctype html>
<html>
<head>
  <title>SimplifiedPHP Test: ' title '</title>
  <style>
    body {
      font-family: "Open Sans", Tahoma, Arial, sans-serif;
      font-size: 15px;
      padding: 0 0.75em;
      color: #333;
    }
    
    h2 {
      font-size: 16px;
    }
    
    h3 {
      margin: 1em 0 0.25em;
      font-size: 11px;
    }
    
    td, th {
      padding: 0.3em 0.5em;
      text-align: center;
      background: rgba(0, 0, 0, 0.1);
    }
    
    td {
      background: rgba(0, 0, 0, 0.05);
    }
    
    pre.code, .output {
      margin-top: 0;
      font-size: 13px;
      background: 'normal';
      border: 1px solid 'dark';
      padding: 12px;
      overflow-x: auto;
      margin-left: 5px;
    }
    
    pre.code {
      box-shadow: -5px 0 0 #a0a;
    }
    
    .output {
      box-shadow: -5px 0 0 0 #0a0;
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
    
    body pre code {
      font-size: 13px;
      background: transparent;
      padding: 0;
      font-family: "Oxygen Mono", monospace;
    }
    
    .btn {
      padding: 0.25em 0.75em;
      margin: 1em -1px 0 0;
      background: 'normal';
      border: 1px solid 'dark';
      border-bottom: none;
      text-decoration: none;
      color: inherit;
      box-shadow: 0 2px 0 0 'highlight';
    }
    
    .btn.large {
      font-size: 120%;
      display: inline-block;
      margin: 0 -1px 1.5em 0;
      padding: 0.5em 1.5em;
    }
    
    .btn:hover {
      background: 'bright';
      position: relative;
    }
    
    .btn:active {
      background: 'dark';
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
      border: 1px solid #c66;
      background: #fdd url(/simplified-php/web/warning.png) no-repeat;
      background-position: 1em;
      color: #a44;
      padding: 2.5em 2em 2.4em 8em;
    }
  </style>
  <link href="http://fonts.googleapis.com/css?family=Oxygen+Mono|Open+Sans:400,700" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/7.3/styles/github.min.css" />
  <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/7.3/highlight.min.js"></script>
  <script>window.hljs.initHighlightingOnLoad();</script>
</head>
<body>
  <a href="https://github.com/NateFerrero/simplified-php"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://s3.amazonaws.com/github/ribbons/forkme_right_green_007200.png" alt="Fork me on GitHub"></a>
  <h1>SimplifiedPHP: ' title '</h1>
'.print

@import "nav-buttons.php"

@finally {'
</body>
</html>
'.print}