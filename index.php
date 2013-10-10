# SimplifiedPHP Home Page
# @author Nate Ferrero

title: "Home"
nav: "home"

@import "web/template.php"

'<p>Welcome to SimplifiedPHP. I hope you find it to be a refreshing and fun
language to code with! Please submit all issues to the Github
repository issue tracker.</p>'.print

'<p class="warning">This project is in the proof of concept stage.
It is unfathomably slow, may unpredictably fail in terrible ways,
and is generally dangerous for your health. Use with caution!'.print

"<h3>SimplifiedPHP source code running this page:</h3>".print
'<pre class="code"><code class="php">'(@request.file.read.html)'</pre></code>'.print
