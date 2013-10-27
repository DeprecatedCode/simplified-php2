# Test group registration

two: 2
three: 3

group: (two + three)

group_self: "Foo" (@self.two)

@test{ group = 5 }
@test{ group_self = "Foo2" }
