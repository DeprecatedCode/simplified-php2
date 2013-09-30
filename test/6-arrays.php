# Summary: Arrays are an ordered list created with square brackets.
# Arrays, like objects, are deferred. Applying an object iterates.

title: "Arrays", @import "test.php"

# Line Break

br: '<br /><br />'

# You can operate on the entire array at once

["A ", "simple ", "array", br].print

# Or one at a time

["Another", "simple", "array"]{it "... " .print}, br.print

# Render as JSON

five_numbers: [1, 2, 3, 4, 5]
five_numbers.to_json br .print

# You can do math

five_numbers{2 * it + 10}.to_json.print, br.print

# And running totals: array object --> array

sum: 0
["Running totals: ", five_numbers{sum: sum + it, sum}.to_json, br].print

# Sum an array: object array --> mixed

"Sum: ".print, '<b>' ({sum: sum + it, sum} five_numbers) '</b>'.print