<?php
class ProductCategoryModel extends Model
{
    private $tableFields = array(
        "id",
        "cat_name",
        "cat_des",
        "created_by",
        "created_at",
        "updated_by",
        "updated_at",
        "status_cat"
    );
    public function getProductCategoryDetailsById($productCategoryId)
    {
        $query = $this->db->prepare("SELECT procat.*, c_usr.name as cname, 
            c_usr.sname as csname, u_usr.name as uname, u_usr.sname as usname 
            FROM product_category as procat
            LEFT JOIN users as c_usr ON c_usr.id = procat.created_by 
            LEFT JOIN users as u_usr ON u_usr.id = procat.updated_by
            WHERE procat.id = :productCategoryId");
        $query->bindParam(':productCategoryId', $productCategoryId);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $result['created_at'] = Helper::getDateTimeByFormat($result["created_at"], 'm/d/Y h:i:s a');
        $result['updated_at'] = (!empty($result["updated_at"])) ? Helper::getDateTimeByFormat($result["updated_at"], 'm/d/Y h:i:s a') : "";

        return $result;
    }

    public function createProductCategory($data)
    {
        try {
            $fields = [];
            $placeholders = [];

            if (!empty($this->categoryNameExistOrNot($data['cat_name']))) {
                return [
                    'data' => $data,
                    'msg' => "Category Name is exist. Please type valid short name.",
                    'status' => "cat_name",
                ];
            }

            $data['created_by'] = $this->user_id;
            $data['created_at'] = date("Y-m-d H:i:s");
            foreach ($data as $key => $item) {
                if (in_array($key, $this->tableFields)) {
                    $fields[] = $key;
                    $placeholders[] = ':' . $key;
                }
            }

            $fieldList = implode(",", $fields);
            $placeholderList = implode(",", $placeholders);
            // var_dump($fieldList);
            // var_dump($placeholders);
            // var_dump($placeholderList);

            $sql = "INSERT INTO product_category ($fieldList) VALUES ($placeholderList)";
            // Assume $pdo is your PDO connection object
            $stmt = $this->db->prepare($sql);
            foreach ($data as $key => $item) {
                if (in_array($key, $this->tableFields)) {
                    $stmt->bindValue(':' . $key, $item);
                }
            }
            $selectedData = [];
            if ($stmt->execute()) {
                // Fetch and display the inserted data
                // You can change the condition here
                // Get the last inserted ID
                $lastInsertedId = $this->db->lastInsertId();
                $selectSql = "SELECT * FROM product_category WHERE id = :productCategoryId";
                $selectStmt = $this->db->prepare($selectSql);
                // Use the appropriate key
                $selectStmt->bindValue(':productCategoryId', $lastInsertedId);
                $selectStmt->execute();
                $selectedData = $selectStmt->fetch(PDO::FETCH_ASSOC);

                // Display the selected data
                return [
                    'data' => $selectedData,
                    'msg' => "You data has been saved successfully!",
                    'status' => true,
                ];
            } else {
                $errorInfo = $stmt->errorInfo();
                return [
                    'data' => $selectedData,
                    'msg' => "You data has not been saved successfully!" . ((isset($errorInfo[2]) && !empty($errorInfo[2])) ? "PDO Error: " . $errorInfo[2] : ""),
                    'status' => false,
                ];
            }
        } catch (PDOException $e) {
            return [
                'data' => [],
                'msg' => "PDO Error: " . $e->getMessage(),
                'status' => false,
            ];
        }
    }

    public function updateProductCategory($data)
    {
        try {
            $updateFields = [];
            $updatePlaceholders = [];
            $categoryNameExistOrNot = $this->categoryNameExistOrNot($data['cat_name']);
            if (!empty($categoryNameExistOrNot) && $categoryNameExistOrNot["id"] != $data['pro_cat_id']) {
                return [
                    'data' => $data,
                    'msg' => "Category Name is exist. Please type valid short name.",
                    'status' => "cat_name",
                ];
            }
            $data['updated_by'] = $this->user_id;
            $data['updated_at'] = date("Y-m-d H:i:s");

            foreach ($data as $key => $item) {
                if (in_array($key, $this->tableFields)) {
                    $updateFields[] = "$key = :$key";
                    $updatePlaceholders[":$key"] = $item;
                }
            }

            $updateSet = implode(", ", $updateFields);

            // UPDATE query
            $sql = "UPDATE product_category SET $updateSet WHERE id = :productCategoryId";
            // Assume $pdo is your PDO connection object
            $stmt = $this->db->prepare($sql);
            // Bind parameters
            foreach ($updatePlaceholders as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':productCategoryId', $data['pro_cat_id']);
            $selectedData = [];
            if ($stmt->execute()) {
                // Fetch and display the updated data
                $selectSql = "SELECT * FROM product_category WHERE id = :productCategoryId";
                $selectStmt = $this->db->prepare($selectSql);
                $selectStmt->bindValue(':productCategoryId', $data['pro_cat_id']);
                $selectStmt->execute();
                $selectedData = $selectStmt->fetch(PDO::FETCH_ASSOC);
                return [
                    'data' => $selectedData,
                    'msg' => "Your data has been updated successfully!",
                    'status' => true,
                ];
            } else {
                $errorInfo = $stmt->errorInfo();
                return [
                    'data' => $selectedData,
                    'msg' => "You data has not been saved successfully!" . ((isset($errorInfo[2]) && !empty($errorInfo[2])) ? "PDO Error: " . $errorInfo[2] : ""),
                    'status' => false,
                ];
            }
        } catch (PDOException $e) {
            return [
                'data' => [],
                'msg' => "PDO Error: " . $e->getMessage(),
                'status' => false,
            ];
        }
    }

    public function deleteProductCategory($productCategoryId)
    {
        try {
            $productCategoryItem = $this->getProductCategoryDetailsById($productCategoryId);
            // var_dump($productCategoryId);
            // var_dump($productCategoryItem);
            if ($productCategoryItem) {
                $query = $this->db->prepare("DELETE FROM product_category WHERE id = :productCategoryId");
                $query->bindParam(':productCategoryId', $productCategoryId);
                if ($query->execute()) {
                    return [
                        'data' => $productCategoryItem,
                        'msg' => "Your Product Category has been deleted successfully!",
                        'status' => true,
                    ];
                } else {
                    $errorInfo = $query->errorInfo();
                    return [
                        'data' => $productCategoryItem,
                        'msg' => "You Product Category has not been deleted successfully!" . ((isset($errorInfo[2]) && !empty($errorInfo[2])) ? "PDO Error: " . $errorInfo[2] : ""),
                        'status' => false,
                    ];
                }
            } else {
                return [
                    'data' => $productCategoryItem,
                    'msg' => "Your Product Category has not been deleted successfully!",
                    'status' => false,
                ];
            }
        } catch (PDOException $e) {
            return [
                'data' => [],
                'msg' => "PDO Error: " . $e->getMessage(),
                'status' => false,
            ];
        }
    }

    public function categoryNameExistOrNot($catName)
    {
        $query = $this->db->prepare("SELECT * FROM product_category WHERE cat_name = :catName");
        $query->bindParam(':catName', $catName);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
