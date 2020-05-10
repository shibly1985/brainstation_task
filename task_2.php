 
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Brain Station Task 2</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>Task 2</h2>
  <p>We’ve uncountable child in each parent category and a child might be a parent of another category.. </p>
  <hr/>
  <?php
	require_once "db/dbconfig.php";
function fetchCategoryTreeList($parent = null, $user_tree_array = '')
{
  global $pdo;
  if (!is_array($user_tree_array))
    $user_tree_array = array();

  $sql = "SELECT
				category.Id,
				category.`Name`,
				category.Number,
				catetory_relations.ParentcategoryId,
				catetory_relations.categoryId,
				(
					SELECT
						COUNT( * ) 
					FROM
						item
						LEFT JOIN item_category_relations ON item.Number = item_category_relations.ItemNumber 
					WHERE
						item_category_relations.categoryId = category.Id 
				) total_items 
			FROM
				category
				LEFT JOIN catetory_relations ON category.Id = catetory_relations.categoryId 
			WHERE
				1 = 1";
  if ($parent == null) {
    $sql .= " AND catetory_relations.ParentcategoryId IS NULL";
  } else {
    $sql .= " AND catetory_relations.ParentcategoryId = $parent";
  }
  $result = $pdo->query($sql);
  $rows = $result->fetchAll(PDO::FETCH_OBJ);
  $num_rows = count($rows);
  if ($num_rows > 0) {
    $user_tree_array[] = "<ul>";
    foreach ($rows as $row) {
      $user_tree_array[] = "<li>" . $row->Name . " (" . $row->total_items . ")</li>";
      $user_tree_array = fetchCategoryTreeList($row->Id, $user_tree_array);
    }
    $user_tree_array[] = "</ul>";
  }
  return $user_tree_array;
}
	?>  
  <ul>
  <?php
  $res = fetchCategoryTreeList();
  foreach ($res as $r) {
    echo $r;
  }
  ?>
</ul>
</div>

</body>
</html>
