[

  {nav: "home", path: "/simplified-php/",                     title: "Home"}
  {nav: "test", path: "/simplified-php/test/1-variables.php", title: "Tests"}

]{'<a class="btn large '{nav = ? (it.nav): 'active'}'"
      href="'(it.path)'">'(it.title)'</a>'.print}

'<br/>'.print
