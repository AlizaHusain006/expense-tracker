<?php
include "db.php";

/* Default Budget */
$budget = 1000;

/* Update budget if user submits form */
if(isset($_POST['new_budget'])){
    $budget = (int)$_POST['new_budget'];
}

/* Total spent */
$totalQuery = $conn->query("SELECT SUM(amount) AS total FROM expenses");
$totalRow = $totalQuery->fetch_assoc();
$totalSpent = $totalRow['total'] ? $totalRow['total'] : 0;

$remaining = $budget - $totalSpent;

/* Expense list */
$result = $conn->query("SELECT * FROM expenses ORDER BY date DESC");

/* Category totals for chart */
$chartQuery = $conn->query("
SELECT category, SUM(amount) AS total
FROM expenses
GROUP BY category
");

$categories = [];
$totals = [];

while($row = $chartQuery->fetch_assoc()){
$categories[] = $row['category'];
$totals[] = $row['total'];
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Finance Dashboard</title>

<link rel="stylesheet" href="style.css">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="charts.js"></script>

</head>


<body>

<!-- Sidebar -->
<div class="sidebar">

<h2 class="logo">Finance</h2>

<ul>
<li><a href="dashboard.php">Dashboard</a></li>
<li><a href="#add-expense">Add Expense</a></li>
<li><a href="#expense-table">Expenses</a></li>
<li><a href="#budget-section">Budget</a></li>
</ul>

</div>


<!-- Main Content -->

<div class="main">


<!-- Top Stats -->

<div class="top-cards">

<div class="card stat">
<h3>Total Spent</h3>
<p>₹<?php echo $totalSpent; ?></p>
</div>

<div class="card stat">
<h3>Budget</h3>
<p>₹<?php echo $budget; ?></p>
</div>

<div class="card stat">
<h3>Remaining</h3>
<p>₹<?php echo $remaining; ?></p>
</div>


<!-- Dashboard Grid -->

<div class="grid">


<!-- Add Expense Form -->

<div class="card" id="add-expense">

<h2>Add Expense</h2>

<form action="add_expense.php" method="POST">

<label>Amount</label>
<input type="number" name="amount" required>

<label>Category</label>
<input type="text" name="category" required>

<label>Description</label>
<textarea name="description"></textarea>

<label>Date</label>
<input type="date" name="date" required>

<button type="submit">Add Expense</button>

</form>

</div>



<!-- Category Pie Chart -->

<div class="card">

<h2>Spending by Category</h2>

<div class="chart-box">
<canvas id="expenseChart"></canvas>
</div>

</div>



<!-- Expense Table -->

<div class="card" id="expense-table">

<h2>All Expenses</h2>

<table>

<tr>
<th>Amount</th>
<th>Category</th>
<th>Description</th>
<th>Date</th>
</tr>

<?php while($row = $result->fetch_assoc()) { ?>

<tr>
<td>₹<?php echo $row['amount']; ?></td>
<td><?php echo $row['category']; ?></td>
<td><?php echo $row['description']; ?></td>
<td><?php echo $row['date']; ?></td>
</tr>

<?php } ?>

</table>

</div>

<!-- Budget Chart -->

<div class="card" id="budget-section">

<h2>Budget Usage</h2>

<form method="POST">

<label>Set Budget</label>
<input type="number" name="new_budget" placeholder="Enter budget">

<button type="submit">Update Budget</button>

</form>

<div class="chart-box">
<canvas id="budgetChart"></canvas>
</div>

</div>


<!-- Send Data to charts.js -->

<script>

const categories = <?php echo json_encode($categories); ?>;
const totals = <?php echo json_encode($totals); ?>;

const budget = <?php echo $budget; ?>;
const spent = <?php echo $totalSpent; ?>;

loadCategoryChart(categories, totals);
loadBudgetChart(budget, spent);

</script>


</body>
</html>