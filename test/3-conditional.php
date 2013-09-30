# Summary: Conditional objects allow for code branching. Only the
# first matching condition is executed.

title: "Conditional", @import "test.php"

# Line Break

br: '<br /><br />'

# Basic conditional object

a: 7

"Variable a is " {a ? < 4: "less than", = 4: "equal to", > 4: "greater than"}" 4!" br.print

# Using conditions with iteration

desires: ["cookie", "burger", "milkshake"]

desires{
  "I " {?key > 0: "also"} " want a " it " " {
    it = ? "cookie": "and a glass of milk"
           "burger": "with mustard"
        "milkshake": "to bring all the boys to the yard"
  } ". ".print
}