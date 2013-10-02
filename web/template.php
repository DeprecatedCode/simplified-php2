# SimplifiedPHP Page Template
# @author Nate Ferrero

# Colors

bright: '#ffffff'
normal: '#f9fbfd'
dark:   '#e9ebed'

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
    
    h3, h4, h5 {
      margin-top: 2em;
    }
    
    pre, .output {
      font-size: 13px;
      background: 'normal';
      border: 1px solid 'dark';
      padding: 12px;
      overflow-x: auto;
    }
    
    body pre code {
      font-size: 13px;
      background: transparent;
      padding: 0;
      font-family: "Oxygen Mono", monospace;
    }
    
    .btn {
      padding: 0.25em 0.75em;
      margin: 1em -1px 1em 0;
      background: 'normal';
      border: 1px solid 'dark';
      text-decoration: none;
      color: inherit;
    }
    
    .btn.large {
      font-size: 120%;
      display: inline-block;
      margin: 0 -1px 1.5em 0;
      padding: 0.5em 1.5em;
    }
    
    .btn:hover {
      background: 'bright';
      box-shadow: inset 0 0 0 2px #8cf;
      border-color: #8cf;
      position: relative;
    }
    
    .btn:active {
      background: 'dark';
    }
    
    .btn.active {
      background: #8cf;
      border-color: #6ad;
      color: #001;
      position: relative;
      z-index: 1;
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