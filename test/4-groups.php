# Summary: Groups are enclosed in parenthesis, and can
# affect the order of operations used in executing code.

title: "Groups", @import "test.php"

# There is a difference between the following a and b:

a: "A: " + 3 + 5

b: "B: " + (3 + 5)

'<p>' a '</p><p>' b '</p>'.print

_flush_()

# There is a difference between the following c and d:

c: "<p>C: " + "A sentence.</p>" .print

d: "Another sentence.</p>" + ("<p>D: ".print) .print
