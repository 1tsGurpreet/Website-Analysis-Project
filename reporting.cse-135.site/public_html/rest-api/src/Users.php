<?php
namespace Src;

class Users {
  private $db;
  private $requestMethod;
  private $postId;

  public function __construct($db, $requestMethod, $postId)
  {
    $this->db = $db;
    $this->requestMethod = $requestMethod;
    $this->postId = $postId;
  }

  public function randomString($n)
  {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
  
    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }
  
    return $randomString;
  }

  public function processRequest()
  {
    switch ($this->requestMethod) {
      case 'GET':
        if ($this->postId) {
          $response = $this->getPost($this->postId);
        } else {
          $response = $this->getAllPosts();
        };
        break;
      case 'POST':
        $response = $this->createPost();
        break;
      case 'PUT':
        $response = $this->updatePost($this->postId);
        break;
      case 'DELETE':
        $response = $this->deletePost($this->postId);
        break;
      default:
        $response = $this->notFoundResponse();
        break;
    }
    header($response['status_code_header']);
    if ($response['body']) {
        echo $response['body'];
    }
  }

  public function find($id)
  {
    $query = "
      SELECT
          *
      FROM
          users
      WHERE id = :id;
    ";

    try {
      $statement = $this->db->prepare($query);
      $statement->execute(array('id' => $id));
      $result = $statement->fetch(\PDO::FETCH_ASSOC);
      return $result;
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  

  private function getAllPosts()
  {
    $query = "
      SELECT id, username, email, passcode, rights  FROM users;";

    try {
      $statement = $this->db->query($query);
      $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }

    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode($result);
    return $response;
  }

  private function getPost($id)
  {
    $result = $this->find($id);
    if (! $result) {
        return $this->notFoundResponse();
    }
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode($result);
    return $response;
  }

  private function getID($id)
  {
    $result = $this->find($id);
    if (! $result) {
        return $this->notFoundResponse();
    }
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode($result);
    return $response;
  }

  private function createPost()
  {
    $input = (array) json_decode(file_get_contents('php://input'), TRUE);
    // if (! $this->validatePost($input)) {
    //   return $this->unprocessableEntityResponse();
    // }

    // $salt = $this->randomString(8);
    // $to_be_hashed_password = concatenate password from zingrid with salt
    // $hashed_password = hash the concatenation
      
    
    $query = "
      INSERT INTO users
          (username, email, salt, passcode, rights)
      VALUES
          (:username, :email, :salt, :passcode, :rights); //admin will put the password in passcode and we will hash it and create a salt for it
    ";

    try {
      $statement = $this->db->prepare($query);
      $salt = $this->randomString(8);
      $password = $input['passcode'];
      $string_to_be_hashed = $password . $salt;
      $hashedPassword = hash('sha256', $string_to_be_hashed);
      $email = $input['email'];
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format"; 
            $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
            $response['body'] = json_encode(array('message' => $emailErr));
        return $response;
}
      if ($statement->execute(array(
        'username' => $input['username'],
        'email'  => $email,
        'salt' => $salt,
        'passcode' => $hashedPassword,
        'rights' => $input['rights']
      )))
        $message = 'User added ';
      else {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode(array('message' => 'Failed'));
        return $response;
      }
      $statement->rowCount();
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }

    $response['status_code_header'] = 'HTTP/1.1 201 Created';
    $response['body'] = json_encode(array('message' => $message));
    return $response;
  }

  private function updatePost($id)
  {
    $result = $this->find($id);
    if (! $result) {
      return $this->notFoundResponse();
    }
    $input = (array) json_decode(file_get_contents('php://input'), TRUE);
    // if (! $this->validatePost($input)) {
    //   return $this->unprocessableEntityResponse();
    // }
    $statement2 = $this->db->prepare("SELECT salt FROM users WHERE id = $id");
    $statement2->execute();
    $result2 = $statement2->fetch(\PDO::FETCH_ASSOC);
    $salt = $result2['salt'];
  
    $username = $input['username'];
    $email = $input['email'];
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format"; 
            $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
            $response['body'] = json_encode(array('message' => $emailErr));
        return $response;
      }
    $password = $input['passcode'];
    $string_to_be_hashed = $password . $salt;
    $hashedPassword = hash('sha256', $string_to_be_hashed);
    $rights = $input['rights'];
      

    $query = "UPDATE users SET username = '$username', email = '$email', passcode = '$hashedPassword', rights = $rights WHERE id = $id";

    try {
      $statement = $this->db->prepare($query);
      if ($statement->execute())
      {
    
      //ASK PROFESSOR IF CAN DISBALE ONE OF THE ZINGRID EDITS
        $message = 'User updated'; }
      else {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode(array('message' => 'Failed'));
        return $response;
      }
      $statement->rowCount();
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }

    $response['status_code_header'] = 'HTTP/1.1 201 Created';
    $response['body'] = json_encode(array('message' => $message));
    return $response;
  }

  private function deletePost($id)
  {
    $result = $this->find($id);
    if (! $result) {
      return $this->notFoundResponse();
    }

    $query = "
      DELETE FROM users
      WHERE id = :id;
    ";

    try {
      $statement = $this->db->prepare($query);
      $statement->execute(array('id' => $id));
      $statement->rowCount();
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode(array('message' => 'User Deleted!'));
    return $response;
  }

  
  private function validatePost($input)
  {
    if (! isset($input['title'])) {
      return false;
    }
    if (! isset($input['body'])) {
      return false;
    }

    return true;
  }

  private function unprocessableEntityResponse()
  {
    $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
    $response['body'] = json_encode([
      'error' => 'Invalid input'
    ]);
    return $response;
  }

  private function notFoundResponse()
  {
    $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
    $response['body'] = null;
    return $response;
  }
}
