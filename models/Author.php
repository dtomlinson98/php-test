<?php
    class Author {
        // DB stuff
        private $conn;
        private $table = 'authors';

        // Author properties
        public $id;
        public $author;

        // setter
        public function __construct($db) {
            $this->conn = $db;
        }

        // getter
        public function read() {

            //define query
            $query = "SELECT
                      id,
                      author
                      FROM
                      {$this->table}
                      ORDER BY 
                      id";

            //prepare query
            $stmt = $this->conn->prepare($query);

            //execute query 
            $stmt->execute();

           //fetching all authors 
            $authorsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

              // Cast the 'id' field to string in each author data
            foreach ($authorsData as &$author) {
                $author['id'] = (string) $author['id'];
            }

            return $authorsData;
        }

        //get single author
        public function read_single() {

            //define query
            $query = "SELECT
                      id,
                      author
                      FROM
                      {$this->table}
                      WHERE id = ?
                      LIMIT 1";
            //prepare query
            $stmt = $this->conn->prepare($query);

            //bind 
            $stmt->bindParam(1, $this->id);
        
            $stmt->execute();
           
           //if author found
            if ($stmt->rowCount() > 0) {
                // Fetch author
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->id = $row['id'];
                $this->author = $row['author'];
                return true;
                //author not found
            } else {
                return false; 
            }
        }

        //create author
        public function create() {
           
            //if author not provided send out message and don't post
            if (empty($this->author)) {
                return array("message" => "Missing Required Parameters");
            }
            //define query
            $query = "INSERT INTO 
                     {$this->table} (author)
                      VALUES (:author)";
                                             
            // prepare stmt
            $stmt = $this->conn->prepare($query);

            //clean data
            $this->author = htmlspecialchars(strip_tags($this->author));

            //bind data
            $stmt->bindParam(':author', $this->author);

            //execute query
            $executeResult = $stmt->execute();

            //execute query
            if($executeResult) {
                //if stmt executes author_id will be set to the last inserted id in the table
                $author_id = $this->conn->lastInsertId();
                //then that value will be used in the response
                $response = array(
                    'id' => $author_id,
                    'author' => $this->author,
                    'message' => 'Author Created');
                return $response;
            } else { 
                //if execute fails then messsage will be returned
                $response = array("message" => "Statement Execute Failed");
                return $response;
                
            }
        }

        //update author
        public function update() {

            //define query
            $query = "UPDATE {$this->table}
                      SET 
                          author = :author
                      WHERE id = :id";
                                              
            //prepare stmt
            $stmt = $this->conn->prepare($query);

            //clean data
            $this->author = htmlspecialchars(strip_tags($this->author));
            $this->id = htmlspecialchars(strip_tags($this->id));

            //bind data
            $stmt->bindParam(':author', $this->author);
            $stmt->bindParam(':id', $this->id);

            //execute query
            //wrapping in conditional to set to true or false
            if($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }

        //delete author
        public function delete() {

            //define query
            $query = "DELETE FROM {$this->table} WHERE id = :id";
            
            //prepare stmt
            $stmt = $this->conn->prepare($query);

            //clean data
            $this->id = htmlspecialchars(strip_tags($this->id));

            //bind data
            $stmt->bindParam(':id', $this->id);

            //execute query
            if ($stmt->execute()) {
                return true;
            } else {

            return false;
            }
        }
    }
?>
