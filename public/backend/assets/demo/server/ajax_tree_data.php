<?php
  //if($_POST['messages'] && is_array($_POST['messages']) && $_POST['messages'][1] == 2)
	$data = [
		{
		    "id": "node_1",
		    "icon": "fas fa-file text-primary",
		    "text": "Node 1",
		    "children": true
		},
		{
		    "id": "node_2",
		    "icon": "fas fa-file text-warning",
		    "text": "Node 2",
		    "children": true
		},
		{
		    "id": "node_3",
		    "icon": "fas fa-file text-info",
		    "text": "Node 3",
		    "children": true
		},
	];
	echo json_encode($data);
  //else echo 66;
?>
