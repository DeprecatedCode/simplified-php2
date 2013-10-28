[

  {nav: "home",     path: base,                        title: "Home"}
  {nav: "test",     path: base "test/1-variables.php", title: "Tests"}
  {nav: "tutorial", path: base "web/tutorial/",        title: "Tutorial"}
  {nav: "plugins",  path: base "web/plugins.php",      title: "Plugins"}
  {nav: "support",  path: "https://github.com/NateFerrero/simplified-php/issues?state=open"
    title: "Bugs &amp; Support"}

] @ {'<a class="btn large '{nav = ? (it.nav): 'active'}'"
      href="'(it.path)'">'(it.title)'</a>'.print}
