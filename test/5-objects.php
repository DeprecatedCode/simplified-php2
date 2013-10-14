# Summary: Objects are useful for creating re-usable functionality
# and encapsulating data.

title: "Objects", @import "test.php"

# If an object ends with a statement, it will be returned upon invocation

foo: {a: 4, b: 5, a + b}, ['foo: ', foo, '<br/><br/>foo(): ', foo()].print

_flush_()

# Apply an object to an object

fn: {a+b}

fn{a:3, b:4}().print, ' and '.print

fn{a:10}{b:20}().print

_flush_()

# The & sign includes a variable in an object

x_pos: 433

pos: {&x_pos, y_pos: x_pos + 100}

pos.print
