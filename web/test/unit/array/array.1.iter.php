# Test array iteration with objects

arr: 1..5

@test{ arr 0 = 1 }
@test{ arr 1 = 2 }
@test{ arr 2 = 3 }
@test{ arr 3 = 4 }
@test{ arr 4 = 5 }

iter: arr @ {value: it}

@test{ iter 0 = {it: 1, key: 0, value: 1} }
@test{ iter 1 = {it: 2, key: 1, value: 2} }
@test{ iter 2 = {it: 3, key: 2, value: 3} }
@test{ iter 3 = {it: 4, key: 3, value: 4} }
@test{ iter 4 = {it: 5, key: 4, value: 5} }

iter2: arr @ { {value: it} }

@test{ iter2 0 = {value: 1} }
@test{ iter2 1 = {value: 2} }
@test{ iter2 2 = {value: 3} }
@test{ iter2 3 = {value: 4} }
@test{ iter2 4 = {value: 5} }
