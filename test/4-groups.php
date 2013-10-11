# Summary: Groups are enclosed in parenthesis, and can
# affect the order of operations used in executing code.

title: "Groups", @import "test.php"

# Line Break

br: '<br /><br />'

# There is a difference between the following a and b:

a: "A: " + 3 + 5

b: "B: " + (3 + 5)

a br b .print

_flush_()

# There is a difference between the following c and d:

c: "C: " + "A sentence." br .print

d: "Another sentence." + ("D: ".print) .print
