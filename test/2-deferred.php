# Summary: Execution of objects and arrays is always deferred
# until the value is needed by another part of your program.

title: "Deferred", @import "test.php"

# Line Break

br: '<br /><br />'

# Objects and arrays are not evaluated until requested

fourth: ["This is printed 4th".print]

second: {third: "This is printed 3rd" br, "This is printed 2nd" br .print}

first: "This is printed 1st" br .print

# Run

second.third.print

fourth[0]