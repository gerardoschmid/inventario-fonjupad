<?php 	

require_once 'core.php';


$sql = "SELECT brand_id, brand_name, brand_active, brand_status FROM brands WHERE brand_status = 1";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll();

$output = array('data' => array());

if(count($result) > 0) {

 $activeBrands = ""; 

 foreach($result as $row) {
 	$brandId = $row[0];
 	// active 
 	if($row[2] == 1) {
 		// activate member
		$activeBrands = '<span class="px-3 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1 bg-[#E6F4EA] text-[#008F39]">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#008F39]"></span>
                            Disponible
                        </span>';
 	} else {
 		// deactivate member
		$activeBrands = '<span class="px-3 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1 bg-error-container text-on-error-container">
                            <span class="w-1.5 h-1.5 rounded-full bg-error"></span>
                            No Disponible
                        </span>';
 	}

	$button = '
	<div class="btn-group">
	  <button type="button" class="p-2 hover:bg-surface-variant rounded-full text-outline transition-colors dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	    <span class="material-symbols-outlined">more_vert</span>
	  </button>
	  <ul class="dropdown-menu dropdown-menu-right rounded-xl shadow-lg border-outline-variant py-2">
	    <li><a type="button" class="flex items-center px-4 py-2 hover:bg-surface-container-low text-body-md font-body-md" data-toggle="modal" data-target="#editBrandModel" onclick="editBrands('.$brandId.')"> <i class="glyphicon glyphicon-edit mr-2"></i> Editar</a></li>
	    <li><a type="button" class="flex items-center px-4 py-2 hover:bg-error-container text-error text-body-md font-body-md" data-toggle="modal" data-target="#removeMemberModal" onclick="removeBrands('.$brandId.')"> <i class="glyphicon glyphicon-trash mr-2"></i> Eliminar</a></li>
	  </ul>
	</div>';

 	$output['data'][] = array( 		
		'<div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-secondary-container flex items-center justify-center">
                <span class="material-symbols-outlined text-on-secondary-container text-sm">home_work</span>
            </div>
            <span class="text-body-lg font-headline-sm text-primary">'.$row[1].'</span>
        </div>',
 		$activeBrands,
		'<div class="text-right">'.$button.'</div>'
 		); 	
 } // /foreach

} // if count



echo json_encode($output);