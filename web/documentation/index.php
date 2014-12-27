title: "Documentation", doc: "home", @import "template.php"

"""<h2>Getting Started</h2>

<p>SimplifiedPHP runs anywhere that <a href="http://www.php.net/">PHP</a> runs, including Linux, Windows, and Mac OS. It's easy to get started!</p>

<p>Please see the <a href="installation.php">Installation Guide</a> to learn how to set up SimplifiedPHP.</p>

<h2>Command Line Usage</h2>

<p><code>sphp -c "1 + 2.print"</code> &mdash; Executes SimplifiedPHP code.</p>
<p><code>sphp foo.php</code> &mdash; Assumes foo.php is SimplifiedPHP code, and executes it.</p>
<p><code>sphp --init</code> &mdash; Setup a new project .htaccess with SimplifiedPHP for Apache.</p>

<h2>Language Example Tests</h2>

<p>For examples of SimplifiedPHP code in action, see the following tests:</p>
<ul>
""" (
  @import '../test/test-sections.php' @ {
    '<li><a href="../test/' (it.path) '.php">' (it.title) ' Test</a></li>'
  }.join ''
) """
</ul>

<h2>Help! How do I do X in SimplifiedPHP?</h2>

<p>See the PHP vs Python vs JS vs SimplifiedPHP <a href="comparison.php">Language Comparison</a>.</p>

<p>To get more help, tweet to <a href="https://twitter.com/intent/tweet?text=%40NateFerrero%20%23simplifiedphp%20Help%20me!">@NateFerrero</a>.</p>

""".print
