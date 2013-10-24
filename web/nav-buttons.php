[

  {nav: "home",     path: "/simplified-php/",                     title: "Home"}
  {nav: "test",     path: "/simplified-php/test/1-variables.php", title: "Tests"}
  {nav: "tutorial", path: "/simplified-php/web/tutorial/",        title: "Tutorial"}
  {nav: "plugins",  path: "/simplified-php/web/plugins/",         title: "Plugins"}

]{'<a class="btn large '{nav = ? (it.nav): 'active'}'"
      href="'(it.path)'">'(it.title)'</a>'.print}

'<br/><br/>'.print
