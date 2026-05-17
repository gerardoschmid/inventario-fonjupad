<?php 	

require_once 'core.php';
require_once 'db_connect_pdo.php';

$sql = "SELECT user_id, username FROM users WHERE user_id != 1";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll();

$output = array('data' => array());

if(count($result) > 0) {

 foreach($result as $row) {
	$userId = $row[0];

	$button = '
	<div class="btn-group">
	  <button type="button" class="p-2 hover:bg-surface-variant rounded-full text-outline transition-colors dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	    <span class="material-symbols-outlined">more_vert</span>
	  </button>
	  <ul class="dropdown-menu dropdown-menu-right rounded-xl shadow-lg border-outline-variant py-2">
	    <li><a type="button" class="flex items-center px-4 py-2 hover:bg-surface-container-low text-body-md font-body-md" data-toggle="modal" data-target="#editUserModal" onclick="editUser('.$userId.')"> <i class="glyphicon glyphicon-edit mr-2"></i> Editar</a></li>
	    <li><a type="button" class="flex items-center px-4 py-2 hover:bg-error-container text-error text-body-md font-body-md" data-toggle="modal" data-target="#removeUserModal" onclick="removeUser('.$userId.')"> <i class="glyphicon glyphicon-trash mr-2"></i> Eliminar</a></li>
	  </ul>
	</div>';

 	$output['data'][] = array( 		
		'<div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center overflow-hidden">
                <img src="https://ui-avatars.com/api/?name='.$row[1].'&background=131b2e&color=fff" class="w-full h-full object-cover">
            </div>
            <span class="text-body-lg font-headline-sm text-primary">'.$row[1].'</span>
        </div>',
		'<div class="text-right">'.$button.'</div>'
 		); 	
 } // /foreach

} // if count


echo json_encode($output);