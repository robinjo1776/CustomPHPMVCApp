<?php
class ProductModel extends Model
{
    private $tableFields = array(
        "id",
        "code",
        "cat_id",
        "scat_id",
        "description",
        "price",
        "pd",
        "bbd",
        "minw",
        "maxw",
        "unit",
        "upc",
        "item_in_box",
        "comments",
        "size_des",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by",
        "status_prod"
    );
    public function getProductDetailsByProductNo($productId)
    {
        $query = $this->db->prepare("SELECT products.*, c_usr.name as cname, 
            c_usr.sname as csname, u_usr.name as uname, u_usr.sname as usname 
            FROM products as products
            LEFT JOIN users as c_usr ON c_usr.id = products.created_by 
            LEFT JOIN users as u_usr ON u_usr.id = products.updated_by
            WHERE products.id = :productId");
        $query->bindParam(':productId', $productId);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $result['created_at'] = (!empty($result["created_at"])) ? Helper::getDateTimeByFormat($result["created_at"], 'm/d/Y h:i:s a') : "";
        $result['updated_at'] = (!empty($result["updated_at"])) ? Helper::getDateTimeByFormat($result["updated_at"], 'm/d/Y h:i:s a') : "";
        $result['pd'] = (!empty($result["pd"]) && $result["pd"] != "0000-00-00") ? Helper::getDateTimeByFormat($result["pd"], 'm/d/Y') : "";
        $result['bbd'] = (!empty($result["bbd"]) && $result["bbd"] != "0000-00-00") ? Helper::getDateTimeByFormat($result["bbd"], 'm/d/Y') : "";

        return $result;
    }

    public function createProduct($data)
    {
        try {
            $fields = [];
            $placeholders = [];

            if (!empty($this->productCodeExistOrNot($data['code']))) {
                return [
                    'data' => $data,
                    'msg' => "Product code is exist. Please type valid product code",
                    'status' => "code",
                ];
            }

            $data['created_by'] = $this->user_id;
            $data['item_in_box'] = (isset($data['item_in_box']) && !empty($data['item_in_box'])) ? $data['item_in_box'] : 0;
            $data['created_at'] = date("Y-m-d H:i:s");
            $data['pd'] = (isset($data['pd']) && !empty($data['pd'])) ? Helper::getDateTimeByFormat($data['pd'], "Y-m-d") : "0000-00-00";
            $data['bbd'] = (isset($data['bbd']) && !empty($data['bbd'])) ? Helper::getDateTimeByFormat($data['bbd'], "Y-m-d") : "0000-00-00";

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

            $sql = "INSERT INTO products ($fieldList) VALUES ($placeholderList)";
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
                $selectSql = "SELECT * FROM products WHERE id = :proId";
                $selectStmt = $this->db->prepare($selectSql);
                // Use the appropriate key
                $selectStmt->bindValue(':proId', $lastInsertedId);
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

    public function updateProduct($data)
    {
        try {
            $updateFields = [];
            $updatePlaceholders = [];
            $productCodeExistOrNot = $this->productCodeExistOrNot($data['code']);
            if (!empty($productCodeExistOrNot) && $productCodeExistOrNot["id"] != $data['pro_id']) {
                return [
                    'data' => $data,
                    'msg' => "Product code is exist. Please type valid product code",
                    'status' => "code",
                ];
            }

            $data['updated_by'] = $this->user_id;
            $data['item_in_box'] = (isset($data['item_in_box']) && !empty($data['item_in_box'])) ? $data['item_in_box'] : 0;
            $data['updated_at'] = date("Y-m-d H:i:s");
            $data['pd'] = (isset($data['pd']) && !empty($data['pd'])) ? Helper::getDateTimeByFormat($data['pd'], "Y-m-d") : "0000-00-00";
            $data['bbd'] = (isset($data['bbd']) && !empty($data['bbd'])) ? Helper::getDateTimeByFormat($data['bbd'], "Y-m-d") : "0000-00-00";

            foreach ($data as $key => $item) {
                if (in_array($key, $this->tableFields)) {
                    $updateFields[] = "$key = :$key";
                    $updatePlaceholders[":$key"] = $item;
                }
            }

            $updateSet = implode(", ", $updateFields);

            // UPDATE query
            $sql = "UPDATE products SET $updateSet WHERE id = :proId";
            // Assume $pdo is your PDO connection object
            $stmt = $this->db->prepare($sql);
            // Bind parameters
            foreach ($updatePlaceholders as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':proId', $data['pro_id']);
            $selectedData = [];
            if ($stmt->execute()) {
                // Fetch and display the updated data
                $selectSql = "SELECT * FROM products WHERE id = :proId";
                $selectStmt = $this->db->prepare($selectSql);
                $selectStmt->bindValue(':proId', $data['pro_id']);
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

    public function deleteProduct($productId)
    {
        try {
            $productItem = $this->getProductDetailsByProductNo($productId);
            // var_dump($productId);
            // var_dump($productItem);
            if ($productItem) {
                $query = $this->db->prepare("DELETE FROM products WHERE id = :proId");
                $query->bindParam(':proId', $productId);
                if ($query->execute()) {
                    return [
                        'data' => $productItem,
                        'msg' => "Your product has been deleted successfully!",
                        'status' => true,
                    ];
                } else {
                    $errorInfo = $query->errorInfo();
                    return [
                        'data' => $productItem,
                        'msg' => "Your product has not been deleted successfully!" . ((isset($errorInfo[2]) && !empty($errorInfo[2])) ? "PDO Error: " . $errorInfo[2] : ""),
                        'status' => false,
                    ];
                }
            } else {
                return [
                    'data' => $productItem,
                    'msg' => "Your product has not been deleted successfully!",
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
    public function getProductByUPC($upc)
    {
        $upcString = substr($upc, 10, 5);
        $query = $this->db->prepare("SELECT 
                pro.id,
                pro.code,
                pro.description,
                pro.price,
                pro.minw,
                pro.maxw,
                unt.short_name as unit,
                pro.upc,
                pro.item_in_box,
                pro.comments,
                pro.status_prod,
                procat.cat_name as cat_code
            FROM products AS pro
            LEFT JOIN units AS unt ON unt.id = pro.unit 
            LEFT JOIN product_category AS procat ON procat.id = pro.cat_id
            WHERE pro.upc = :upc");
        $query->bindParam(':upc', $upcString);
        $query->execute();
        $result = array(); // Initialize an array to store the results
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row; // Add each row to the result array
        }
        return $result;
    }

    public function productCodeExistOrNot($code)
    {
        $query = $this->db->prepare("SELECT * FROM products WHERE code = :code");
        $query->bindParam(':code', $code);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
