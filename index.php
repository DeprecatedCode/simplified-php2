# SimplifiedPHP Home Page
# @author Nate Ferrero

title: "Home"
nav: "home"

@import "web/template.php"

'<p>Welcome to SimplifiedPHP. I hope you find it to be a refreshing and fun
language to code with! Please submit all issues to the Github
repository issue tracker.</p>'.print

"<h3>SimplifiedPHP source code running this page:</h3>".print
'<pre><code class="php">'(@request.file.read.html)'</pre></code>'.print
