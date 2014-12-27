# Summary: A colon defines a key-value pair within an object.
# These keys can later be accessed with .name or ("string").

title: "Variables", @import "test.php"

# Basic variable

var1: "Hello World 1"

'<p>' var1 '</p>'.print

_flush_()

# Object key accessors

var2: {"first": "Hello ", "second": "World 2"}

'<p>' (var2.first).print, var2("second") '</p>'.print

_flush_()

# Using object variables

var3: {one: 1, two: 2, three: one + two, message: "Hello World " + three}

'<p>' (var3.message) '</p>'.print

_flush_()

# Setter syntax (obj::var value)

var4: {numbers: {}}

var4.numbers::four 4
var4::message ("Hello World " + (var4.numbers.four))

'<p>' (var4.message) '</p>'.print
