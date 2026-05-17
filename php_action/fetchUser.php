<?php 	



require_once 'core.php';
require_once 'db_connect_pdo.php';

$sql = "SELECT * FROM users";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll();

$output = array('data' => array());
if(count($result) > 0) {

 // $row = $result->fetch_array();
 $active = ""; 

 foreach($result as $row) {
 	$userid = $row[0];
 	// active 
 	$username = $row[1];

 	$button = '<!-- Single button -->
	<div class="btn-group">
	  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	    Action <span class="caret"></span>
	  </button>
	  <ul class="dropdown-menu">
	    <li><a type="button" data-toggle="modal" id="editUserModalBtn" data-target="#editUserModal" onclick="editUser('.$userid.')"> <i class="glyphicon glyphicon-edit"></i> Edit</a></li>
	    <li><a type="button" data-toggle="modal" data-target="#removeUserModal" id="removeUserModalBtn" onclick="removeUser('.$userid.')"> <i class="glyphicon glyphicon-trash"></i> Remove</a></li>       
	  </ul>
	</div>';

	

 	$output['data'][] = array( 		
 		// name
 		$username,
 		// button
 		$button 		
 		); 	
 } // /while 

}// if num_rows



echo json_encode($output);