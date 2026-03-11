// Category Spending Pie Chart

function loadCategoryChart(categories, totals){

const ctx = document.getElementById('expenseChart');

new Chart(ctx,{
type:'pie',
data:{
labels:categories,
datasets:[{
data:totals,
backgroundColor:[
"#d4af37",
"#d5bc76",
"#9f781f",
"#837456",
"#f3daae",
"#3c280b",
"#9f9587"
]
}]
}
});

}

function loadBudgetChart(budget, spent){

const ctx = document.getElementById('budgetChart');

new Chart(ctx,{
type:'doughnut',
data:{
labels:["Spent","Remaining"],
datasets:[{
data:[spent, budget-spent],
backgroundColor:[
"#d4af37",
"#2a2a2a"
],
borderWidth:0
}]
},
options:{
cutout:"70%",
plugins:{
legend:{
labels:{
color:"white"
}
},
tooltip:{
callbacks:{
label:function(context){
return "₹ " + context.raw;
}
}
}
}
}
});

}