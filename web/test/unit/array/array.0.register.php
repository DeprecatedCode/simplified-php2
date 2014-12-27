# Test array registration

two: 2
three: 3

arr: [1, three - two + 1, three]

@test{ arr 0 = 1 }
@test{ arr 1 = 2 }
@test{ arr 2 = 3 }

@test{ arr [0]        = [1] }
@test{ arr [1, 2]     = [2, 3] }
@test{ arr [2, 1, 0]  = [3, 2, 1] }
