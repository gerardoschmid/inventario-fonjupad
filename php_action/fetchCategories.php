<?php 	

require_once 'core.php';
require_once 'db_connect_pdo.php';

$sql = "SELECT categories_id, categories_name, categories_active, categories_status FROM categories WHERE categories_status = 1";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll();

$output = array('data' => array());

if(count($result) > 0) {

 // $row = $result->fetch_array();
 $activeCategories = ""; 

 foreach($result as $row) {
 	$categoriesId = $row[0];
 	// active 
 	if($row[2] == 1) {
 		// activate member
		$activeCategories = "<label class='label label-success'>Disponible</label>";
 	} else {
 		// deactivate member
		$activeCategories = "<label class='label label-danger'>Not Disponible</label>";
 	}

 	$button = '<!-- Single button -->
	<div class="btn-group">
	  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	    Action <span class="caret"></span>
	  </button>
	  <ul class="dropdown-menu">
	    <li><a type="button" data-toggle="modal" id="editCategoriesModalBtn" data-target="#editCategoriesModal" onclick="editCategories('.$categoriesId.')"> <i class="glyphicon glyphicon-edit"></i> Edit</a></li>
	    <li><a type="button" data-toggle="modal" data-target="#removeCategoriesModal" id="removeCategoriesModalBtn" onclick="removeCategories('.$categoriesId.')"> <i class="glyphicon glyphicon-trash"></i> Remove</a></li>       
	  </ul>
	</div>';

 	$output['data'][] = array( 		
 		$row[1], 		
 		$activeCategories,
 		$button 		
 		); 	
 } // /while 

}// if num_rows



echo json_encode($output);