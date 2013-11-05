title: "Language Comparison", doc: "comparison", @import "template.php"

# List of languages in desired order

languages: {
  php : 'PHP'
  py  : 'Python'
  js  : 'JavaScript'
  sphp: 'SimplifiedPHP'
  res : 'Result'
}

# Examples in each language

table: [

  {title: "Loops", examples: [
    
    {php  : '$array = array(1, 2, 3, 4, 5);'
     py   : 'array = [1, 2, 3, 4, 5]'
     js   : 'array = [1, 2, 3, 4, 5];'
     sphp : 'array: [1, 2, 3, 4, 5]',
     res  : [1, 2, 3, 4, 5].to_json
    }
  
    {php  : 'foreach ($array as $item) {\n  echo $item;\n}'
     py   : 'for item in array:\n  print item'
     js   : 'array.forEach(function(item) {\n\tconsole.log(item);\n});'
     sphp : 'array @ {it.print}',
     res  : [1, 2, 3, 4, 5] @ {it}
    }
    
    {php  : '$total = 0;\nforeach($array as $item) {\n  $total += $item;\n}\necho $total;'
     py   : 'total = 0\nfor item in array:\n  total += item\nprint total'
     js   : 'total = 0;\narray.forEach(function (item) {\n  total += item;\n});\nconsole.log(total);'
     sphp : 'total: 0\narray @ {++total it}\ntotal.print',
     res  : {total: 0, [1, 2, 3, 4, 5] @ {++total it}, total}$
    }
  ]}

] @ {
  '<tr><th colspan="'(languages @ {key}.length)'">' (it.title) '</th></tr>
   <tr>' (languages @ {'<th>' it '</th>'}) '</tr>' (
    it.examples @ {
      example: it, '<tr>' (languages @ {
        '<td><pre><code>' (example(key)) '</code></code></td>'
      }) '</tr>'
    }
  )
}

"""<table>{{table}}</table>""" {&table} .print
