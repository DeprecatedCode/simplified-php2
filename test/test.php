# SimplifiedPHP Test Page Runner
# Author: Nate Ferrero

title: title " Test"
nav: "test"

@import "../web/template.php"

sections: @import "test-sections.php"

sections {'<a class="btn '{it.path '.php' = ? (@request.basename): 'active'}'"
              href="' (it.path) '.php">' (it.title) '</a>'.print}
              
'<br/><br/>'.print
              
count: 0

before: '<h3>SimplifiedPHP Code:</h3><pre class="code"><code class="php">'
middle: '</code></pre><h3>Result:</h3><div class="output">'
after: '</div>'

source: @request.file.read.html.split "_flush_()"

@parent::_flush_ {
  ? count > 0: after.print
  before.print
  source[++count 1].trim.print
  middle.print
}

_flush_() # Spit out the first SimplifiedPHP code before running the test

@finally {
  after.print
  '<h4>Test completed in ' (@timer) 'ms</h4>'.print
}