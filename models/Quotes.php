<?php
    class Quote {
        // DB stuff
        private $conn;
        private $table = 'quotes';
    
        // Quote properties
        public $id;
        public $quote;
        public $author_id;
        public $category_id;

        // setter
        public function __construct($db) {
            $this->conn = $db;
        }

        // getter
        public function read() {
            // Define query
            $query = "SELECT
            q.id,
            q.quote,
            a.author AS author,
            c.category AS category,
            q.author_id,
            q.category_id
          FROM
            {$this->table} q
          LEFT JOIN
            authors a ON q.author_id = a.id
          LEFT JOIN
            categories c ON q.category_id = c.id";

            // Prepare query
            $stmt = $this->conn->prepare($query);

            // Execute query 
            $stmt->execute();

            return $stmt;
        }

        // Get single quote
        public function read_single() {
            // Define query
            $query = "SELECT
                q.id,
                q.quote,
                a.author AS author,
                c.category AS category,
                q.author_id,
                q.category_id
              FROM
                {$this->table} q
              LEFT JOIN
                authors a ON q.author_id = a.id
              LEFT JOIN
                categories c ON q.category_id = c.id
              WHERE 
                q.id = ?
              LIMIT 1";

            // Prepare query
            $stmt = $this->conn->prepare($query);

            // Bind id
            $stmt->bindParam(1, $this->id);
        
            // Execute query 
            $stmt->execute();

            //fethc row
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $row;
}

        // Create quote
        public function create() {

            //if any parameters are not set
            if (empty($this->quote) || empty($this->author_id) || empty($this->category_id)) {
                return array("message" => "Missing Required Parameters");
            }

            $authorExists = $this->authorExists();
            $categoryExists = $this->categoryExists();

            //if both are invalid 
            if (!$authorExists && !$categoryExists) {
                return array("message" => "author_id and category_id Not Found");
            }
            
            //if just author invalid
            if (!$this->authorExists()) {
                return array("message" => "author_id Not Found");
            }

            //if jsut category invalid
            if (!$this->categoryExists()) {
                return array("message" => "category_id Not Found");
            }

            // Define query
            $query = "INSERT INTO {$this->table} (quote, author_id, category_id)
            VALUES (:quote, :author_id, :category_id)";

            // Prepare query
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->quote = htmlspecialchars(strip_tags($this->quote));
            $this->author_id = htmlspecialchars(strip_tags($this->author_id));
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));

            // Bind data
            $stmt->bindParam(':quote', $this->quote);
            $stmt->bindParam(':author_id', $this->author_id);
            $stmt->bindParam(':category_id', $this->category_id);

            // Execute query
            if($stmt->execute()) {
                $this->id = $this->conn->lastInsertId();

                // Prepare the response
                $response = array(
                    'id' => $this->id,
                    'quote' => $this->quote,
                    'author_id' => $this->author_id,
                    'category_id' => $this->category_id
                );
    
                return $response;
        }
    }

    private function authorExists() {
        $query = "SELECT id FROM authors WHERE id = :author_id LIMIT 1";
        
        //prepare
        $stmt = $this->conn->prepare($query);
        
        //bind
        $stmt->bindParam(':author_id', $this->author_id);
        
        //execute
        $stmt->execute();
        
        //true if greater than zero else false
        return $stmt->rowCount() > 0;
    }

    private function categoryExists() {
        $query = "SELECT id FROM categories WHERE id = :category_id LIMIT 1";
        
        //prepare
        $stmt = $this->conn->prepare($query);
        
        //bind
        $stmt->bindParam(':category_id', $this->category_id);
        
        //execute
        $stmt->execute();

        //true if greater than zero else false
        return $stmt->rowCount() > 0;
    }

        // Update quote
        public function update() {

            //if any parameters are empty
            if (empty($this->quote) || empty($this->author_id) || empty($this->category_id)) {
                return array("message" => "Missing Required Parameters");
            }
    
            //checks for author and category
            $authorExists = $this->authorExists();
            $categoryExists = $this->categoryExists();
    
            if (!$authorExists && !$categoryExists) {
                return array("message" => "author_id and category_id Not Found");
            }
    
            //just author_id is invalid
            if (!$authorExists) {
                return array("message" => "author_id Not Found");
            }
    
            //just category_id is invalid
            if (!$categoryExists) {
                return array("message" => "category_id Not Found");
            }
            // Define query
            $query = "UPDATE {$this->table}
            SET 
                quote       = :quote,
                author_id   = :author_id,
                category_id = :category_id
            WHERE id = :id";

            // Prepare query
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->quote = htmlspecialchars(strip_tags($this->quote));
            $this->author_id = htmlspecialchars(strip_tags($this->author_id));
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind data
            $stmt->bindParam(':quote', $this->quote);
            $stmt->bindParam(':author_id', $this->author_id);
            $stmt->bindParam(':category_id', $this->category_id);
            $stmt->bindParam(':id', $this->id);

            // Execute query
            if ($stmt->execute()) {
                return array(
                    'id' => $this->id,
                    'quote' => $this->quote,
                    'author_id' => $this->author_id,
                    'category_id' => $this->category_id
                );
            } else {
                return array("message" => "Quote Not Updated");
            }
        }

        // Delete quote
        public function delete() {

            if ($this->quoteExists()) {
                
            
                //define query
                $query = "DELETE FROM {$this->table} WHERE id = :id";
                
                //prepare stmt
                $stmt = $this->conn->prepare($query);

                //clean data
                $this->id = htmlspecialchars(strip_tags($this->id));

                //bind data
                $stmt->bindParam(':id', $this->id);

                //execute query
                if($stmt->execute()) {
                    return true;
                }
            } else {
                return false;
            }
        }
        //function checking if quote id exists
         public function quoteExists() {
            //define query
            $query = "SELECT id FROM {$this->table} WHERE id = :id";
            
            //prepare stmt
            $stmt = $this->conn->prepare($query);
            
            //clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            
            //bind data
            $stmt->bindParam(':id', $this->id);
            
            //execute query
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return true; 
            } else {
                return false;
            }
        } 
    }
?>
