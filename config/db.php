<?php
	// Include config.php file
	include_once 'config.php';

	// Create a class Users
	class Database extends Config {
		
	  // Fetch all or a single user from database
	  public function fetch($id = 0) {
	    $sql = 'SELECT * FROM users';
	    if ($id != 0) {
	      $sql .= ' WHERE id = :id';
	    }
	    $stmt = $this->conn->prepare($sql);
	    $stmt->execute(['id' => $id]);
	    $rows = $stmt->fetchAll();
	    return $rows;

	  }

	  // Insert an user in the database
	  public function insert($name, $email, $phone) {

	    $sql = 'INSERT INTO users (name, email, phone) VALUES (:name, :email, :phone)';
	    $stmt = $this->conn->prepare($sql);
	    if($stmt->execute(['name' => $name, 'email' => $email, 'phone' => $phone])){
			return true;
		}else{
			return false;
		}

	  }

	  // Update an user in the database
	  public function update($name, $email, $phone, $id) {

		$updated_at = date('Y-m-d H:i:s');

	    $sql = 'UPDATE users SET name = :name, email = :email, phone = :phone, updated_at = :updated_at WHERE id = :id';
	    $stmt = $this->conn->prepare($sql);
	    if($stmt->execute(['name' => $name, 'email' => $email, 'phone' => $phone, 'updated_at' => $updated_at, 'id' => $id])){
			return true;
		}else{
			return false;
		}

	  }

	  // Delete an user from database
	  public function delete($id) {

	    $sql = 'DELETE FROM users WHERE id = :id';
	    $stmt = $this->conn->prepare($sql);
	    if($stmt->execute(['id' => $id])){
			return true;
		}else{
			return false;
		}

	  }

	//   Check if email already exists
	public function checkemail($user_eamil){

		$sql = "SELECT * FROM users WHERE email = :email";
		$stmt = $this->conn->prepare($sql);
		$stmt->execute(['email' => $user_eamil]);
		// $result = $stmt->fetch(PDO::FETCH_ASSOC);

		if($stmt->rowCount() > 0){
			return true;
		}else{
			return false;
		}
	}

	}

?>
