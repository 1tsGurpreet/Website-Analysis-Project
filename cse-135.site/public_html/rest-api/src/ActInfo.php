<?php
namespace Src;

class ActInfo {
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
          activity;
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
    //echo ($input['mousePosition'][0]['timestamp']);
    foreach ($input['mousePosition'] as $pos){
       $session = '"' . $input['session'] . '"';
       $pageEnter = $input['timing']['pageEnter'];
       $pageLeave = ($input['timing']['pageLeave'] == null) ? -1 : $input['timing']['pageLeave'];
       $currPage = $input['timing']['currPage'];  
       $timestamp = $pos['timestamp'];
       $altkey = ($pos['altKey']) ? 1 : 0;
       $ctrlkey = ($pos['ctrlKey']) ? 1 : 0;
       $shiftkey = ($pos['shiftKey']) ? 1 : 0;
       $clientx = $pos['coordinates']['clientX'];
       $clienty = $pos['coordinates']['clientY'];
       $layerx = $pos['coordinates']['layerX'];
       $layery = $pos['coordinates']['layerY'];
       $offsetx = $pos['coordinates']['offsetX'];
       $offsety = $pos['coordinates']['offsetY'];
       $pagex = $pos['coordinates']['pageX'];
       $pagey = $pos['coordinates']['pageY'];
       $screenx = $pos['coordinates']['screenX'];
       $screeny = $pos['coordinates']['screenY'];
       $x = $pos['coordinates']['x'];
       $y = $pos['coordinates']['y'];

       $query = "
        INSERT INTO activity
           (session, type, pageEnter, pageLeave, currPage, timestamp, altkey, ctrlkey, shiftkey, clientx, clienty, layerx, layery, offsetx, offsety, pagex, pagey, screenx, screeny, x, y)
        VALUES
           ($session, \"position\", $pageEnter, $pageLeave, '$currPage', $timestamp, $altkey, $ctrlkey, $shiftkey, $clientx, $clienty, $layerx, $layery, $offsetx,$offsety, $pagex, $pagey, $screenx, $screeny, $x, $y)
      ";

      try{
        $statement = $this->db->prepare($query);
        if ($statement->execute() )
          $message = 'Post Created';
        else
          $message = 'Failed';
        $statement->rowCount();

      } catch (\PDOException $e) {
        exit($e->getMessage());
      }
    }

    foreach ($input['mouseClicks'] as $pos){
       $session = '"' . $input['session'] . '"';
       $pageEnter = isset($input['timing']['pageEnter']) ? $input['timing']['pageEnter'] : 'null';
       $pageLeave = ($input['timing']['pageLeave'] == null) ? -1 : $input['timing']['pageLeave'];
       $currPage = isset($input['timing']['currPage']) ? $input['timing']['currPage'] : 'null';
       $timestamp = isset($pos['timestamp']) ? $pos['timestamp'] : 'null';
       $altkey = ($pos['altKey']) ? 1 : 0;
       $ctrlkey = ($pos['ctrlKey']) ? 1 : 0;
       $shiftkey = ($pos['shiftKey']) ? 1 : 0;
       $clientx = isset($pos['coordinates']['clientX']) ? $pos['coordinates']['clientX'] : 'null';
       $clienty = isset($pos['coordinates']['clientY']) ? $pos['coordinates']['clientY'] : 'null';
       $layerx = isset($pos['coordinates']['layerX']) ? $pos['coordinates']['layerX'] : 'null';
       $layery = isset($pos['coordinates']['layerY']) ? $pos['coordinates']['layerY'] : 'null';
       $offsetx = isset($pos['coordinates']['offsetX']) ? $pos['coordinates']['offsetX'] : 'null';
       $offsety = isset($pos['coordinates']['offsetY']) ? $pos['coordinates']['offsetY'] : 'null';
       $pagex = isset($pos['coordinates']['pageX']) ? $pos['coordinates']['pageX'] : 'null';
       $pagey = isset($pos['coordinates']['pageY']) ? $pos['coordinates']['pageY'] : 'null';
       $screenx = isset($pos['coordinates']['screenX']) ? $pos['coordinates']['screenX'] : 'null';
       $screeny = isset($pos['coordinates']['screenY']) ? $pos['coordinates']['screenY'] : 'null';
       $x = isset($pos['coordinates']['x']) ? $pos['coordinates']['x'] : 'null';
       $y = isset($pos['coordinates']['y']) ? $pos['coordinates']['y'] : 'null';

       $query = "
        INSERT INTO activity
           (session, type, pageEnter, pageLeave, currPage, timestamp, altkey, ctrlkey, shiftkey, clientx, clienty, layerx, layery, offsetx, offsety, pagex, pagey, screenx, screeny, x, y)
        VALUES
           ($session, \"click\", $pageEnter, $pageLeave, '$currPage', $timestamp, $altkey, $ctrlkey, $shiftkey, $clientx, $clienty, $layerx, $layery, $offsetx,$offsety, $pagex, $pagey, $screenx, $screeny, $x, $y)
      ";

      try{
        $statement = $this->db->prepare($query);
        if ($statement->execute() )
          $message = 'Post Created';
        else
          $message = 'Failed';
        $statement->rowCount();

      } catch (\PDOException $e) {
        exit($e->getMessage());
      }
    }

  

    foreach ($input['keystrokes']['keydown'] as $pos){ //access key up or key down 
      $keyDirection = "keydown";
      $keyStroke = $pos['key'];
      $code = $pos['code'];
      $session = '"' . $input['session'] . '"';
      $pageEnter = $input['timing']['pageEnter'];
      $pageLeave = ($input['timing']['pageLeave'] == null) ? -1 : $input['timing']['pageLeave'];
      $currPage = $input['timing']['currPage'];
      $timestamp = $pos['timestamp'];
      $altkey = ($pos['altKey']) ? 1 : 0;
      $ctrlkey = ($pos['ctrlKey']) ? 1 : 0;
      $shiftkey = ($pos['shiftKey']) ? 1 : 0;

      $query = "
       INSERT INTO activity
          (session, type, pageEnter, pageLeave, currPage, timestamp, altkey, ctrlkey, shiftkey, keyDirection, keyStroke, code)
       VALUES
          ($session, \"key\", $pageEnter, $pageLeave, $currPage, $timestamp, $altkey, $ctrlkey, $shiftkey, $keyDirection, $keyStroke, $code)
     ";

     try{
       $statement = $this->db->prepare($query);
       if ($statement->execute() )
         $message = 'Post Created';
       else
         $message = 'Failed';
       $statement->rowCount();

     } catch (\PDOException $e) {
       exit($e->getMessage());
     }
  }

  foreach ($input['keystrokes']['keyup'] as $pos){ //access key up or key down 
    $keyDirection = "keyup";
    $keyStroke = $pos['key'];
    $code = $pos['code'];
    $session = '"' . $input['session'] . '"';
    $pageEnter = $input['timing']['pageEnter'];
    $pageLeave = ($input['timing']['pageLeave'] == null) ? -1 : $input['timing']['pageLeave'];
    $currPage = $input['timing']['currPage'];
    $timestamp = $pos['timestamp'];
    $altkey = ($pos['altKey']) ? 1 : 0;
    $ctrlkey = ($pos['ctrlKey']) ? 1 : 0;
    $shiftkey = ($pos['shiftKey']) ? 1 : 0;

    $query = "
     INSERT INTO activity
        (session, type, pageEnter, pageLeave, currPage, timestamp, altkey, ctrlkey, shiftkey, keyDirection, keyStroke, code)
     VALUES
        ($session, \"key\", $pageEnter, $pageLeave, $currPage, $timestamp, $altkey, $ctrlkey, $shiftkey, $keyDirection, $keyStroke, $code)
   ";

   try{
     $statement = $this->db->prepare($query);
     if ($statement->execute() )
       $message = 'Post Created';
     else
       $message = 'Failed';
     $statement->rowCount();

   } catch (\PDOException $e) {
     exit($e->getMessage());
   }
}
    $response['status_code_header'] = 'HTTP/1.1 201 Created';
    $response['body'] = json_encode(array('message' => 'Post Created' /*, 'echo' => var_dump($input['mousePosition'])*/ ) );
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
      DELETE FROM activity
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
         activity
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
