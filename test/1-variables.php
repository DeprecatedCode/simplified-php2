title: "Variables", @import "test.php"

# Line Break

br: '<br /><br />'

# Test 1-1

var1: "Hello World 1"
var1.print
br.print

# Test 1-2

var2: {"first": "Hello ", "second": "World 2"}
var2.first.print, var2["second"].print
br.print

# Test 1-3

var3: {one: 1, two: 2, three: one + two, message: "Hello World " + three}
var3.message.print
