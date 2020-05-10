<?php

class Ecommerce {

    private $db;
	
    function __construct($DB_con) {
        $this->db = $DB_con;
    }

    public function dd($data) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        die();
    }

    

    public function sQuery($sql) {
        $e = $this->db->prepare($sql);
        $e->execute(array());
        $data = array();
        while ($r = $e->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $r;
        }
		return $data;
    }

    public function getTaskOne() {
		$sql = "SELECT
						catetory_relations.ParentcategoryId,
						catetory_relations.categoryId,
						category.`name`,
						COUNT(icr.ItemNumber) as total_item
						FROM
							`catetory_relations`
						LEFT JOIN category on category.id=catetory_relations.ParentcategoryId
						LEFT JOIN item_category_relations as icr ON icr.categoryId=catetory_relations.categoryId						
						GROUP BY category.`name`
						ORDER BY catetory_relations.ParentcategoryId desc";
		$exQry = $this->sQuery($sql);       
        return $exQry;
    }
	
	
	
	
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
		  $e = $this->db->prepare($sql);
			$e->execute(array());
		  $rows = $e->fetchAll(PDO::FETCH_OBJ);
		  $num_rows = count($rows);
		  if ($num_rows > 0) {
			$user_tree_array[] = "<ul>";
			foreach ($rows as $row) {
			  $user_tree_array[] = "<li>" . $row->Name . " (" . $row->total_items . ")</li>";
			  $user_tree_array = $this->fetchCategoryTreeList($row->Id, $user_tree_array);
			}
			$user_tree_array[] = "</ul>";
		  }
		  return $user_tree_array;
		}
	
	
	public function getTaskTwo($parent = null, $user_tree_array = '') {
		
		$res = $this->fetchCategoryTreeList();
		$this->dd($res);
		return $res;
		
}
}
?>
