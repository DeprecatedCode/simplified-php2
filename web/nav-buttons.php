[

  {nav: "home",     path: base,                       title: "Home"}
  {nav: "docs",     path: base "documentation/",  title: "Documentation"}
  {nav: "tutorial", path: base "tutorial/",       title: "Tutorial"}
  {nav: "plugins",  path: base "plugins.php",     title: "Plugins"}
  {nav: "support",  path: "https://github.com/NateFerrero/simplified-php/issues?state=open"
    title: "Bugs &amp; Support"}

] @ {'<a class="btn large '{nav = ? (it.nav): 'active'}'"
      href="'(it.path)'">'(it.title)'</a>'.print}
