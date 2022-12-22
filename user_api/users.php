<?php
	// Include CORS headers
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
	header('Access-Control-Allow-Headers: X-Requested-With');
	header('Content-Type: application/json');

	// Include action.php file
	include_once '../config/db.php';
	// Create object of Users class
	$user = new Database();

	// create an api variable to get HTTP method dynamically
	$api = $_SERVER['REQUEST_METHOD'];

	// get id from url
	$id = intval($_GET['id'] ?? '');

	// Get all or a single user from database
	if ($api == 'GET') {
	  if ($id != 0) {
	    $data = $user->fetch($id);
	  } else {
	    $data = $user->fetch();
	  }
	  echo json_encode($data);
	}

	// Add a new user into database
	if ($api == 'POST') {

		$data = json_decode(file_get_contents('php://input'));

		$name = $user->test_input($data->name);
		$email = $user->test_input($data->email);
		$phone = $user->test_input($data->phone);

		if($user->checkemail($email)){
			http_response_code(400);
			echo $user->message('User email alraedy exist! Please login or choose another email.',false);
		}else{

			if ($user->insert($name, $email, $phone)) {
				http_response_code(4201);
				echo $user->message('User added successfully!', true);
			} else {
				http_response_code(400);
				echo $user->message('Failed to add an user!', false);
			}
		}

	}


	// Update an user in database
	if ($api == 'PUT') {
		
		if ($id != null) {

			$the_user = $user->fetch($id);

			if(count($the_user) > 0){

				$post_input = json_decode(file_get_contents('php://input'));

				$name = isset($post_input->name) ?  $user->test_input($post_input->name) : $the_user[0]['name'];
				$email = isset($post_input->email) ?  $user->test_input($post_input->email) : $the_user[0]['email'];
				$phone = isset($post_input->phone) ?  $user->test_input($post_input->phone) : $the_user[0]['phone'];

				if ($user->update($name, $email, $phone, $id)) {
					echo $user->message('User updated successfully!',true);
				}else{
					echo $user->message('Failed ro update!',true);
				} 
			}else {
				echo $user->message('No User found! with this ID',false);
			}
	  	} else {
	    	echo $user->message('Please provide a valid User ID',false);
	  	}

	}

	// Delete an user from database
	if ($api == 'DELETE') {
		if ($id != null) {
			$the_user = $user->fetch($id);

			if(count($the_user) > 0){
				if ($user->delete($id)) {
					echo $user->message('User deleted successfully!', true);
				} else {
					echo $user->message('Failed to delete an user!', false);
				}		
			}else{
				echo $user->message('No User found for this ID', false);
			}
		} else {
			echo $user->message('Please provide a valid user ID', false);
		}
	}

?>