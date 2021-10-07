<?php
namespace Src;

class StaticInfo {
  private $db;
  private $requestMethod;
  private $postId;

  public function __construct($db, $requestMethod, $postId)
  {
    $this->db = $db;
    $this->requestMethod = $requestMethod;
    $this->postId = $postId;
  }

  public function processRequest()
  {
    switch ($this->requestMethod) {
      case 'GET':
        if ($this->postId) {
          $response = $this->getID($this->postId);
        } else {
          $response = $this->getAll();
        };
        break;
      case 'POST':
        $response = $this->createPost();
        break;
      /*case 'PUT':
        $response = $this->updatePost($this->postId);
        break;*/
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

  private function getAll()
  {
    $query = "
      SELECT
          *
      FROM
          static;
    ";

    try {
      $statement = $this->db->query($query);
      if ($statement)
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
      else 
        $result = 'Fail';
    } catch (\PDOException $e) {
      exit($e->getMessage());
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
    // For now, assume valid input, TODO: implement validatePost
    /*if (! $this->validatePost($input)) {
      return $this->unprocessableEntityResponse();
    }*/

    $query = "
      INSERT INTO static
         (userAgent, language, acceptsCookies, innerWidth, innerHeight, outerWidth, outerHeight, downlink, effectiveType, rtt, saveData, session, dateTime)
      VALUES
          (:userAgent, :language, :acceptsCookies, :innerWidth, :innerHeight, :outerWidth, :outerHeight, :downlink, :effectiveType, :rtt, :saveData, :session, :dateTime);
    ";

    try {
      $statement = $this->db->prepare($query);
      $cookies = ($input['acceptsCookies']) ? 1 : 0;
      $saveData = ($input['connection']['saveData']) ? 1 : 0;
      if ($statement->execute(array(
        'userAgent' =>  '"' . $input['userAgent'] . '"',
        'language' => '"' . $input['language'] . '"',
        // 'userAgent' => $input['userAgent'],
        // 'language' =>  $input['language'],  
        'acceptsCookies' => $cookies, 
        'innerWidth' => $input['screenDimmensions']['inner']['innerWidth'], 
        'innerHeight' => $input['screenDimmensions']['inner']['innerHeight'], 
        'outerWidth' => $input['screenDimmensions']['outer']['outerWidth'], 
        'outerHeight' => $input['screenDimmensions']['outer']['outerHeight'], 
        'downlink' => $input['connection']['downlink'],
        'effectiveType' => '"' . $input['connection']['effectiveType'] . '"', 
        'rtt' => $input['connection']['rtt'], 
        'saveData' => $saveData,  
        'session' => $input['session'],
        'dateTime' => '"' . $input['dateTime'] . '"'
      )) )
        $message = 'Post Created';  
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
  
  /*
  private function updatePost($id)  
  {
    $result = $this->find($id);
    if (! $result) {
      return $this->notFoundResponse();
    }
    $input = (array) json_decode(file_get_contents('php://input'), TRUE);
    if (! $this->validatePost($input)) {
      return $this->unprocessableEntityResponse();
    }

    $statement = "
      UPDATE post
      SET
        title = :title,
        body  = :body,
        author = :author,
        author_picture = :author_picture
      WHERE id = :id;
    ";

    try {
      $statement = $this->db->prepare($statement);
      $statement->execute(array(
        'id' => (int) $id,
        'title' => $input['title'],
        'body'  => $input['body'],
        'author' => $input['author'],
        'author_picture' => 'https://secure.gravatar.com/avatar/'.md5($input['author']).'.png?s=200',
      ));
      $statement->rowCount();
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode(array('message' => 'Post Updated!'));
    return $response;
  }*/

  private function deletePost($id)
  {
    $result = $this->find($id);
    if (! $result) {
      return $this->notFoundResponse();
    }
    $selector = (is_numeric($id)) ? 'id' : 'session';
    if (is_numeric($id))
        $id = (int) $id;
    else
        $id = '"' . $id . '"';


    $query = "
      DELETE FROM static
      WHERE $selector = $id;
    ";

    try {
      $statement = $this->db->prepare($query);
      $statement->execute();
      $statement->rowCount();
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
    $response['status_code_header'] = 'HTTP/1.1 200 OK';
    $response['body'] = json_encode(array('message' => 'Post Deleted!'));
    return $response;
  }

  public function find($id)
  {
    $selector = (is_numeric($id)) ? 'id' : 'session';
    if (is_numeric($id))
        $id = (int) $id;
    else
        $id = '"' . $id . '"';

    $query = "
      SELECT
          *
      FROM
         static
      WHERE $selector = $id;
    ";

    try {
      $statement = $this->db->prepare($query);
      $statement->execute();
      $result = $statement->fetchall(\PDO::FETCH_ASSOC);
      return $result;
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  //TODO: Consider implementing this to check for well formed JSON
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
