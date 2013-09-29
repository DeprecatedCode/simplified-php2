'<!doctype html>
<html>
<head>
  <title>SimplifiedPHP Test: ' title '</title>
  <style>
    body {
      font-family: Oxygen, Tahoma, Arial, sans-serif;
      font-size: 13px;
      padding: 0 0.75em;
      color: #333;
    }
    .input, .output {
      font-size: 12px;
      background: #eee;
      border: 1px solid #ddd;
      padding: 12px;
    }
    
    body pre code {
      font-size: 12px;
      background: transparent;
      padding: 0;
      font-family: "Oxygen Mono", monospace;
    }
    
    .btn {
      padding: 0.25em 0.5em;
      margin: 1em 0.5em 1em 0;
      background: #eee;
      border: 1px solid #ddd;
      text-decoration: none;
      color: inherit;
    }
    
    .btn:hover {
      background: #fff;
      box-shadow: inset 0 0 0 2px #8cf;
      border-color: #8cf;
    }
    
    .btn:active {
      background: #ddd;
    }
    
    .btn.active {
      background: #8cf;
      border-color: #8cf;
    }
  </style>
  <link href="http://fonts.googleapis.com/css?family=Oxygen+Mono|Oxygen:400,700" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/7.3/styles/github.min.css" />
  <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/7.3/highlight.min.js"></script>
  <script>window.hljs.initHighlightingOnLoad();</script>
</head>
<body>
  <h1>SimplifiedPHP Test: ' title '</h1>
'.print

[

  {path: "1-variables", title: "Variables"}

  {path: "2-deferred", title: "Deferred"}

  {path: "3-conditional", title: "Conditional"}

]{'<a class="btn" href="' (it.path) '.php">' (it.title) '</a> '.print}

'
  <h3>SimplifiedPHP Code:</h3>
  <pre class="input"><code class="php">' (@request.file.read.html) '</pre></code>
  
  <h3>Result:</h3>
  <div class="output">

'.print

@finally {'

  </div>
  <p>Test completed in ' (@timer) 'ms</p>
</body>
</html>
'.print}