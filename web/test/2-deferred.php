# Summary: Execution of objects and arrays is always deferred
# until the value is needed by another part of your program.

title: "Deferred", @import "test.php"

# Objects and arrays are not evaluated until requested

fourth: ["<p>This is printed 4th</p>".print]

second: {third: "<p>This is printed 3rd</p>", "<p>This is printed 2nd</p>" .print, foo: "bar"}

first: "<p>This is printed 1st</p>".print

# Run

second.third.print

fourth[0]
