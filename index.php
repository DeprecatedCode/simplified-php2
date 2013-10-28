# SimplifiedPHP Home Page
# @author Nate Ferrero

title: "Home"
nav: "home"

@import "web/template.php"

icon: {'<span class="typcn typcn-' i '"></span>'}

"""
<div style="width: 42em">

  <h2>""" (icon{i: "world"}) """Synopsis</h2>
  
  <p>Simplified PHP is a new approach to creating a programming language that
  works with the widespread distribution, ease of installation, and reliability
  of PHP, while avoiding its overly complicated syntax and nuances.</p>
  
  <h2>""" (icon{i: "book"}) """What can it do?</h2>
  
  <p>SimplifiedPHP is best suited for small-to-medium size websites, APIs, or command line scripts.
  In the future, there may be an optimized engine released for large-scale projects.</p>
  
  <p>The language and framework are limited by the lack of SimplifiedPHP plugins released.
  For database connectivity, there is currently only a <a href="web/plugins.php">mongo</a> plugin;
  if you would like to create a SimplifiedPHP plugin I would be happy to help you get started.</p>
  
  <h2>""" (icon{i: "warning"}) """Bugs &amp; Support</h2>
  
  <p>Please <a href="https://github.com/NateFerrero/simplified-php/issues/new">create a new issue</a>
  on Github to notify the author of any bugs discovered or feature requests.</p>
  
  <p class="warning"><b>This project is in alpha status!</b><br/>It is currently inefficient at best,
  and may fail unpredictably.</p>
  
  <p>""" (icon{i: "edit"}) """Built with enthusiasm by
  <a href="https://twitter.com/nateferrero">@NateFerrero</a> since 2013, and
  released under the <a href="LICENSE">MIT License</a>.</p>
</div>  
""".print
