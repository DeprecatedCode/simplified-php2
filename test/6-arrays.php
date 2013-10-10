# Summary: Arrays are an ordered list created with square brackets.
# Arrays, like objects, are deferred. Applying an object iterates.

title: "Arrays", @import "test.php"

# Line Break

br: '<br /><br />'

# Array length

items: {arr.length " item" {arr.length ? > 1 :"s"}}

arr: [4, 3, 2, 1], "First there are " items "." br.print

arr: [1], "And now there is " items "." br.print

'<--->'.print

freeze: items()

arr: [3, 2, 1]

"Before, there used to be " freeze ", but now there are " items "." br.print

'<--->'.print

# You can operate on the entire array at once

["A ", "simple ", "array", br].print

'<--->'.print

# Or one at a time

["Another", "simple", "array"]{it.upper "... "}.print, br.print

'<--->'.print

# Render as JSON

five_numbers: [1, 2, 3, 4, 5]
five_numbers.to_json br .print

'<--->'.print

# Get the first and last item in an array

first: {@break}five_numbers.it

last: {? key < (five_numbers.length): @continue, it}five_numbers.it

["Complicated method: ", first, " ... ", last, br].print

'<--->'.print

# Or, less creatively

["Simple method: ", five_numbers[0, -1].join " ... ", br].print

'<--->'.print

# You can do math

five_numbers{2 * it + 10}.to_json.print, br.print

# And running totals: array object --> array

sum: 0
["Running totals: ", five_numbers{sum: sum + it, sum}.to_json, br].print

'<--->'.print

# Sum an array: object array --> mixed

"Sum: ".print, '<b>' ({sum: sum + it, sum} five_numbers) '</b>'.print
