<?php
class ProductSubCategoryModel extends Model
{
    private $tableFields = array(
        "id",
        "pcat_id",
        "scat_name",
        "scat_des",
        "created_by",
        "created_at",
        "updated_by",
        "updated_at",
        "status_prov"
    );
    private $productCategoryModel;

    function __construct()
    {
        parent::__construct();
    }
    public function getProductSubCategoryDetailsById($productSubCategoryId)
    {
        $query = $this->db->prepare("SELECT pro_subc.*, pro_cat.cat_name, 
            c_usr.name as cname, c_usr.sname as csname, u_usr.name as uname, 
            u_usr.sname as usname  
        FROM product_subcategory as pro_subc
            LEFT JOIN users as c_usr ON c_usr.id = pro_subc.created_by  
            LEFT JOIN users as u_usr ON u_usr.id = pro_subc.updated_by 
            LEFT JOIN product_category as pro_cat ON pro_cat.id = pro_subc.pcat_id
        WHERE pro_subc.id = :productSubCategoryId");
        $query->bindParam(':productSubCategoryId', $productSubCategoryId);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $result['created_at'] = Helper::getDateTimeByFormat($result["created_at"], 'm/d/Y h:i:s a');
        $result['updated_at'] = (!empty($result["updated_at"])) ? Helper::getDateTimeByFormat($result["updated_at"], 'm/d/Y h:i:s a') : "";

        return $result;
    }

    public function createProductSubCategory($data)
    {
        try {
            $fields = [];
            $placeholders = [];

            if (!empty($this->productSubCategoryNameExistOrNot($data['scat_name']))) {
                return [
                    'data' => $data,
                    'msg' => "Sub category is exist. Please type valid sub category name.",
                    'status' => "scat_name",
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

            $sql = "INSERT INTO product_subcategory ($fieldList) VALUES ($placeholderList)";
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
                $selectSql = "SELECT * FROM product_subcategory WHERE id = :productSubCategoryId";
                $selectStmt = $this->db->prepare($selectSql);
                // Use the appropriate key
                $selectStmt->bindValue(':productSubCategoryId', $lastInsertedId);
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

    public function updateProductSubCategory($data)
    {
        try {
            $updateFields = [];
            $updatePlaceholders = [];
            $shortNameExistOrNot = $this->productSubCategoryNameExistOrNot($data['scat_name']);
            if (!empty($shortNameExistOrNot) && $shortNameExistOrNot["id"] != $data['scat_id']) {
                return [
                    'data' => $data,
                    'msg' => "Sub category is exist. Please type valid sub category name.",
                    'status' => "scat_name",
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
            $sql = "UPDATE product_subcategory SET $updateSet WHERE id = :productSubCategoryId";
            // Assume $pdo is your PDO connection object
            $stmt = $this->db->prepare($sql);
            // Bind parameters
            foreach ($updatePlaceholders as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':productSubCategoryId', $data['scat_id']);
            $selectedData = [];
            if ($stmt->execute()) {
                // Fetch and display the updated data
                $selectSql = "SELECT * FROM product_subcategory WHERE id = :productSubCategoryId";
                $selectStmt = $this->db->prepare($selectSql);
                $selectStmt->bindValue(':productSubCategoryId', $data['scat_id']);
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

    public function deleteProductSubCategory($productSubCategoryId)
    {
        try {
            $productSubCategoryItem = $this->getProductSubCategoryDetailsById($productSubCategoryId);
            // var_dump($productSubCategoryId);
            // var_dump($productSubCategoryItem);
            if ($productSubCategoryItem) {
                $query = $this->db->prepare("DELETE FROM product_subcategory WHERE id = :productSubCategoryId");
                $query->bindParam(':productSubCategoryId', $productSubCategoryId);
                if ($query->execute()) {
                    return [
                        'data' => $productSubCategoryItem,
                        'msg' => "Your item has been deleted successfully!",
                        'status' => true,
                    ];
                } else {
                    $errorInfo = $query->errorInfo();
                    return [
                        'data' => $productSubCategoryItem,
                        'msg' => "You item has not been deleted successfully!" . ((isset($errorInfo[2]) && !empty($errorInfo[2])) ? "PDO Error: " . $errorInfo[2] : ""),
                        'status' => false,
                    ];
                }
            } else {
                return [
                    'data' => $productSubCategoryItem,
                    'msg' => "Your item has not been deleted successfully!",
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

    public function productSubCategoryNameExistOrNot($productSubCategoryName)
    {
        $query = $this->db->prepare("SELECT * FROM product_subcategory WHERE scat_name = :productSubCategoryName");
        $query->bindParam(':productSubCategoryName', $productSubCategoryName);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function getSubCategoryByCatId($catId)
    {
        $query = $this->db->prepare("SELECT * 
            FROM product_subcategory
            WHERE product_subcategory.pcat_id = :catId");
        $query->bindParam(':catId', $catId);
        $query->execute();
        $result = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row;
        }

        return $result;
    }
}
