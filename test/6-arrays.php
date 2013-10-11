# Summary: Arrays are an ordered list created with square brackets.
# Arrays, like objects, are deferred. Applying an object iterates.

title: "Arrays", @import "test.php"

# Line Break

br: '<br /><br />'

# Array length

items: {arr.length " item" {arr.length ? > 1 :"s"}}

arr: [4, 3, 2, 1], "First there are " items "." br.print

arr: [1], "And now there is " items "." .print

_flush_()

freeze: items()

arr: [3, 2, 1]

"Before, there used to be " freeze ", but now there are " items "." .print

_flush_()

# You can operate on the entire array at once

["A ", "simple ", "array"].print

_flush_()

# Or one at a time

["Another", "simple", "array"]{it.upper "... "}.print

_flush_()

# Render as JSON

five_numbers: [1, 2, 3, 4, 5]
five_numbers.to_json.print

_flush_()

# Get the first and last item in an array

first: {@break}five_numbers.it

last: {? key < (five_numbers.length): @continue, it}five_numbers.it

["Complicated method: ", first, " ... ", last].print

_flush_()

# Or, less creatively

["Simple method: ", five_numbers[0, -1].join " ... "].print

_flush_()

# You can do math

five_numbers{2 * it + 10}.join ', '.print

_flush_()

# And running totals: array object --> array

sum: 0
["Running totals: ", five_numbers{sum: sum + it, sum}.to_json].print

_flush_()

# Sum an array: object array --> mixed

"Sum: ".print, '<b>' ({sum: sum + it, sum} five_numbers) '</b>'.print
