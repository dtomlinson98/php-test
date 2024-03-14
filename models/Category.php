<?php
    class Category {
        // DB stuff
        private $conn;
        private $table = 'categories';

        // Category properties
        public $id;
        public $category;

        // setter
        public function __construct($db) {
            $this->conn = $db;
        }

        // getter
        public function read() {
     
            //define query
            $query = "SELECT
                      id,
                      category
                      FROM
                      {$this->table}
                      ORDER BY 
                      id";

            //prepare query
            $stmt = $this->conn->prepare($query);

            //execute query 
            $stmt->execute();

            
            $categoriesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $categoriesData;
        }

        // Get single category
        public function read_single() {
            // Define query
            $query = "SELECT
                      id,
                      category
                      FROM
                      {$this->table}
                      WHERE id = ?
                      LIMIT 1";
            // Prepare query
            $stmt = $this->conn->prepare($query);

            // Bind id
            $stmt->bindParam(1, $this->id);
       
            // Execute query 
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Set properties
            $this->category = $row['category'];
        }

        // Create category
        public function create() {

            if (empty($this->category)) {
                return array("message" => "Missing Required Parameters’");
            }

            // Define query
            $query = "INSERT INTO {$this->table} (category)
            VALUES (:category)";
                                             
            // Prepare query
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->category = htmlspecialchars(strip_tags($this->category));

            // Bind data
            $stmt->bindParam(':category', $this->category);

            // Execute query
            if($stmt->execute()) {
                $id = $this->conn->lastInsertId();
                $response = array(
                    'id' => $id,
                    'category' => $this->category,
                    'message' => 'Category Created'
                );
                return $response;
            } 
        }

        // Update category
        public function update() {
            
            //id and category must be provided to run
            if (empty($this->id) || empty($this->category)) {
                return false;
            }
        
            // Define query
            $query = "UPDATE {$this->table}
                      SET 
                          category = :category
                      WHERE id = :id";
        
            // Prepare query
            $stmt = $this->conn->prepare($query);
        
            // Clean data
            $this->category = htmlspecialchars(strip_tags($this->category));
            $this->id = htmlspecialchars(strip_tags($this->id));
        
            // Bind data
            $stmt->bindParam(':category', $this->category);
            $stmt->bindParam(':id', $this->id);
        
            // if stmt execute successsfully
            if($stmt->execute()) {
                //update() = true
                return true;
            } else {
                //update() = false
                return false;
            }
            
            
        }

        // Delete category
        public function delete() {
          
            //if category doesn't exist delete() = false
            if (!$this->categoryExists()) {
                return false;
                //array("message" => "Category with ID {$this->id} not found");
            }
            //define query
            $query = "DELETE FROM {$this->table} WHERE id = :id";
            
            //prepare stmt
            $stmt = $this->conn->prepare($query);

            //clean data
            $this->id = htmlspecialchars(strip_tags($this->id));

            //bind data
            $stmt->bindParam(':id', $this->id);

            //execute query
            $stmt->execute();
            
            return true;
        
        }
        //function checking if category id exists
         public function categoryExists() {
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
