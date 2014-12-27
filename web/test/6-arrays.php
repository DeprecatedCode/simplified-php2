# Summary: Arrays are an ordered list created with square brackets.
# Arrays, like objects, are deferred. Applying an object iterates.

title: "Arrays", @import "test.php"

# Array length

items: {arr.length " item" {arr.length ? > 1 :"s"}}

arr: [4, 3, 2, 1], "<p>First there are " items ".</p>" .print

arr: [1], "<p>And now there is " items ".</p>" .print

_flush_()

freeze: items()

arr: [3, 2, 1]

"<p>Before, there used to be " freeze ", but now there are " items ".</p>" .print

_flush_()

# If you are careful not to invoke the array, it can contain undefined variables
# at definition time

atomic_mass: [mercury, gold, mercury + gold]
['<p>', atomic_mass {mercury: 200.59, gold: 196.97}.to_json, '</p>'].print

_flush_()

# You can operate on the entire array at once

["<p>A ", "simple ", "array</p>"].print

_flush_()

# Or one at a time by iterating with @

['<p>', ["Another", "simple", "array"] @ {it.upper " ... "}, '</p>'].print

_flush_()

# Render as JSON

five_numbers: [1, 2, 3, 4, 5]
'<p>' (five_numbers.to_json) '</p>' .print

_flush_()

# Get the first and last item in an array

first: {@break}five_numbers.it

last: {? key < (five_numbers.length): @continue, it}five_numbers.it

["<p>Complicated method: ", first, " ... ", last, "</p>"].print

_flush_()

# Or, less creatively

["<p>Simple method: ", five_numbers[0, -1].join " ... ", "</p>"].print

_flush_()

# You can do math

["<p>", five_numbers @ {2 * it + 10}.join ', ', "</p>"].print

_flush_()

# And running totals: array object --> array

sum: 0
["<p>Running totals: ", five_numbers @ {sum: sum + it, sum}.to_json, "</p>"].print

_flush_()

# Sum an array: object array --> mixed

"<p>Sum: ".print, '<b>' ({sum: sum + it, sum} five_numbers) "</b></p>" .print
