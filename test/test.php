# SimplifiedPHP Test Page Runner
# Author: Nate Ferrero

title: title " Test"
nav: "test"

@import "../web/template.php"

sections: @import "test-sections.php"

sections {'<a class="btn '{it.path '.php' = ? (@request.basename): 'active'}'"
              href="' (it.path) '.php">' (it.title) '</a>'.print}

'
  <h3>SimplifiedPHP Code:</h3>
  <pre><code class="php">' (@request.file.read.html) '</pre></code>
  
  <h3>Result:</h3>
  <div class="output">

'.print

@finally {'

  </div>
  <h4>Test completed in ' (@timer) 'ms</h4>
'.print}