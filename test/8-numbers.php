# Summary: Numbers and mathematical operations are executed from left to right in
# the order they are written. Only groups (parenthesis) take precedence.

title: "Numbers", @import "test.php"

br: '<br/><br/>'

'All of the following variables should be 100:' br.print

a: 20 * 5

b: 10 + 20 + 30 + 40

c: 5 ^ 2 * (2 ^ 2)

d: 2 + 2 + 2 + 2 + 2 ^ 2  # All of the addition happens before the power

e: -100 * 2 + 100 * -10 / 10.0

# Let's make a new object and take just the variables we need, and print it out as JSON:

['<pre>', {&a, &b, &c, &d, &e}.to_json, '</pre>'].print

_flush_()

# Incrementing a number: 3 alternatives

show: {' &raquo; ' x .print}

x: 5,             x.print

x: x + 5,         show()

++x 5,            show()

@self::x (x + 5), show()
