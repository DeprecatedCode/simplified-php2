# SimplifiedPHP Test Page Runner
# Author: Nate Ferrero

title: title " Test"
nav: "test"

@import "../web/template.php"

sections: @import "test-sections.php"

loc: {? ?? loc: loc, *: ''}$

format: {? @request.args ?? format: '?format=' (@request.args.format), *: ''}

sections {'<a class="btn '{it.path '.php' = ? (@request.basename): 'active'}'"
              href="' loc (it.path) '.php' format ' ">' (it.title) '</a>'.print}

'<br/><br/>'.print

types: ['code', 'interlaced', 'result']
type: {@request.args ?? format ?: @request.args.format, *: types 1}$

switch: types {
  '<a class="switch-' it {it ? = type: ' selected'} '" href="?format=' it '">' (it.title) '</a>'
}

['<div class="switch">', switch, '</div>'].print

count: 0

before_code:    '<h3>SimplifiedPHP Code:</h3><pre class="code"><code class="php">'
after_code:     '</code></pre>'

before_result:  '<h3>Result:</h3><div class="output">'
after_result:   '</div>'

source: @request.file.read.html.split ~"\R_flush_\(\)"

@parent::_flush_ {
  ? type = 'code' || (type = 'result'): @stop
  ? count > 0: after_result.print
  before_code.print
  source[++count 1].trim.print
  after_code before_result.print
}

@finally {
  '<h4>Test completed in ' (@timer) 'ms</h4>'.print
}

{type = ? \

  'code': {
    [before_code, source, after_code].print
    @exit
  }$

  'result': {
    [before_result].print
    @finally {
      after_result.print
    }
  }$

  *: {
  
    _flush_() # Spit out the first SimplifiedPHP code before running the test

    @finally {
      after_result.print
    }
  }$
}$
