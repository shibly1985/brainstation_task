
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Brain Station Task 1</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>Task 1</h2>
  <p>Show all categories with total item and order categories by total Items (DESC). </p>
  <hr/>
  <?php
	require_once "db/dbconfig.php";

	$stmt = $pdo->query("SELECT
						catetory_relations.ParentcategoryId,
						catetory_relations.categoryId,
						category.`name`,
						COUNT(icr.ItemNumber) as total_item
						FROM
							`catetory_relations`
						LEFT JOIN category on category.id=catetory_relations.ParentcategoryId
						LEFT JOIN item_category_relations as icr ON icr.categoryId=catetory_relations.categoryId
						-- WHERE catetory_relations.ParentcategoryId IN (1,2,3,4)
						GROUP BY category.`name`
						ORDER BY catetory_relations.ParentcategoryId desc");
			
		
	?>  
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Category Name</th>
        <th>Total Item</th>
		</tr>
    </thead>
    <tbody>
	<?php while ($row = $stmt->fetch()) { ?>
	<tr>
        <td><?php echo $row['name']; ?></td>
        <td><?php echo $row['total_item']; ?></td>       
    </tr> 
		
	<?php } ?>
	
           
    </tbody>
  </table>
</div>

</body>
</html>
