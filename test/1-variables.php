# Summary: A colon defines a key-value pair within an object.
# These keys can later be accessed with .name or ("string").

title: "Variables", @import "test.php"

var1: "Hello World 1"
var1.print

_flush_()

var2: {"first": "Hello ", "second": "World 2"}
var2.first.print, var2("second").print

_flush_()

var3: {one: 1, two: 2, three: one + two, message: "Hello World " + three}
var3.message.print
