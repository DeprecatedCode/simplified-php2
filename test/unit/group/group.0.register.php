# Test group registration

two: 2
three: 3

group: (two + three)

group_parent: "Foo" (@parent.two)

@test{ group = 5 }
@test{ group_parent = "Foo2" }
