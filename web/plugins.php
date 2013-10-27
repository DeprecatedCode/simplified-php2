# SimplifiedPHP Plugins Page
# @author Nate Ferrero

title: "Plugins"
nav: "plugins"

@import "template.php"

# Tabs

"""
<style>
  .plugin {
    padding-left: 32px;
  }
  .plugin h1 {
    margin: 0.5em 0 0 -32px;
  }
  .plugin p {
    margin: 0.35em 0;
  }
  .plugin h1 .typcn {
    margin-right: 2px; 
  }
  .plugin p .typcn {
    vertical-align: 1px;
    font-size: 115%;
    margin-right: 0.2em;
    margin-left: 0.75em;
  }
  .plugin p .typcn:first-child {
    margin-left: 0;
  }
  .plugin h1 code {
    font-size: 15px;
    background: #444;
    color: #fff;
    border-radius: 2px;
    padding: 3px 6px;
    vertical-align: 6px;
  }
</style>
""".print

'<br/><br/>'.print

'<a class="btn '{? @request.args ?? directory ! : 'active'}'"
  href="?">Installed Plugins</a>'.print

'<a class="btn '{? @request.args ?? directory   : 'active'}'"
  href="?directory">Directory</a>'.print

'<br/><br/>'.print

icon: {'<span class="typcn typcn-' i '"></span>'}

@dir "../plugins" .dirs {

  dir: it

  '<div class="plugin">'.print

  # Load plugin information
  about: @import(dir.path "/about.php")
  
  # Show the about name, or just the plugin folder name
  plugin: dir.name.replace{"sphp-": ""}
  name: {? about ?? name: about.name, *: plugin}$
  
  # Plugin name and version
  '<h1>' (icon{i: "input-checked"}) \
    name ({? about ?? version: ' ' (about.version)}) ' <code>' \
    '@plugin.' plugin '</code></h1>'.print

  {? about ?? description: '<p>' (icon{i: "puzzle-outline"}) (about.description) '</p>'.print}$
  
  '<p>'.print

    {? about ?? url: (icon{i: "home"}) '<a target="_blank"
      href="' (about.url) '">' (about.url) '</a>'.print
    }$
      
    {? about ?? authors: (icon{i: "pencil"}) (about.authors{
        it ?? ? url: '<a target="_blank" href="' (it.url) '">' (it.name) '</a>'
               name: it.name 
      }.join ", ").print
    }$
  
  {? dir.file "example.php" .exists: (icon{i: "lightbulb"}) \
    '<a href="../plugins/' (dir.name) '/example.php">Example</a>'.print
  }$

  '</p>'.print

  '</div>'.print
}
