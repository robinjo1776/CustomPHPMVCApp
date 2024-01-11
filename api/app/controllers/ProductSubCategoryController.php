<?php
class ProductSubCategoryController extends Controller
{
    private $productSubCategoryModel;
    private $productCategoryModel;
    private $notificationModel;

    function __construct()
    {
        parent::__construct();
        Helper::AddModel("ProductSubCategoryModel");
        Helper::AddModel("ProductCategoryModel");
        Helper::AddModel("NotificationModel");
        $this->productSubCategoryModel = new ProductSubCategoryModel();
        $this->productCategoryModel = new ProductCategoryModel();
        $this->notificationModel = new NotificationModel();
    }

    public function index()
    {
        $response = new Response();
        $this->getResponse()->setStatus(false);
        $this->getResponse()->setCode(400);
        $this->getResponse()->setData([]);
        $this->getResponse()->setShiftArray(false);
        echo $this->getResponse()->getResponseData();
        die();
    }

    public function showProductSubCategoryDetails($productSubCategoryId)
    {
        try {
            $productSubCategoryDetails = $this->productSubCategoryModel->getProductSubCategoryDetailsById($productSubCategoryId);

            $this->getResponse()->setStatus(true);
            $this->getResponse()->setCode(200);
            $this->getResponse()->setData($productSubCategoryDetails);
            $this->getResponse()->setShiftArray(false);
            echo $this->getResponse()->getResponseData();
            die();
        } catch (\Throwable $th) {
            $this->getResponse()->setStatus(false);
            $this->getResponse()->setCode(200);
            $this->getResponse()->setData([]);
            $this->getResponse()->setShiftArray(false);
            $this->getResponse()->setMsg($th->getMessage());
            echo $this->getResponse()->getResponseData();
            die();
        }
    }

    public function createProductSubCategory()
    {
        try {
            if ($this->getRequest()->isPost()) {
                $postData = $this->getRequest()->getPostData();
                $result = $this->productSubCategoryModel->createProductSubCategory($postData);

                $this->getResponse()->setStatus($result['status']);
                $this->getResponse()->setCode(200);
                $this->getResponse()->setData($result['data']);
                $this->response->setMethodMsg($result['msg']);
                $this->getResponse()->setShiftArray(false);
                echo $this->getResponse()->getResponseData();
                die();
            } else {
                $this->getResponse()->setStatus(false);
                $this->getResponse()->setCode(405);
                $this->getResponse()->setData([]);
                $this->getResponse()->setShiftArray(false);
                echo $this->getResponse()->getResponseData();
            }
        } catch (\Throwable $th) {
            $this->getResponse()->setStatus(false);
            $this->getResponse()->setCode(200);
            $this->getResponse()->setData([]);
            $this->getResponse()->setShiftArray(false);
            $this->getResponse()->setMsg($th->getMessage());
            echo $this->getResponse()->getResponseData();
            die();
        }
    }
    public function updateProductSubCategory()
    {
        try {
            if ($this->getRequest()->isPost()) {
                $postData = $this->getRequest()->getPostData();
                $result = $this->productSubCategoryModel->updateProductSubCategory($postData);

                $this->getResponse()->setStatus($result['status']);
                $this->getResponse()->setCode(200);
                $this->getResponse()->setData($result['data']);
                $this->response->setMethodMsg($result['msg']);
                $this->getResponse()->setShiftArray(false);
                echo $this->getResponse()->getResponseData();
                die();
            } else {
                $this->getResponse()->setStatus(false);
                $this->getResponse()->setCode(405);
                $this->getResponse()->setData([]);
                $this->getResponse()->setShiftArray(false);
                echo $this->getResponse()->getResponseData();
            }
        } catch (\Throwable $th) {
            $this->getResponse()->setStatus(false);
            $this->getResponse()->setCode(200);
            $this->getResponse()->setData([]);
            $this->getResponse()->setShiftArray(false);
            $this->getResponse()->setMsg($th->getMessage());
            echo $this->getResponse()->getResponseData();
            die();
        }
    }

    public function deleteProductSubCategory($productSubCategoryId)
    {
        try {
            $result = $this->productSubCategoryModel->deleteProductSubCategory($productSubCategoryId);

            if ($result['status']) {
                $option['message'] = "ProductSubCategory was deleted";
                $this->notificationModel->addNotification($option);
            }
            $this->getResponse()->setStatus($result['status']);
            $this->getResponse()->setCode(200);
            $this->getResponse()->setData($result['data']);
            $this->response->setMethodMsg($result['msg']);
            $this->getResponse()->setShiftArray(false);
            echo $this->getResponse()->getResponseData();
            die();
        } catch (\Throwable $th) {
            $this->getResponse()->setStatus(false);
            $this->getResponse()->setCode(200);
            $this->getResponse()->setData([]);
            $this->getResponse()->setShiftArray(false);
            $this->getResponse()->setMsg($th->getMessage());
            echo $this->getResponse()->getResponseData();
            die();
        }
    }

    public function getProductSubCategory()
    {
        try {
            if ($this->getRequest()->isPost()) {
                $postData = $this->getRequest()->getRawData();
                // var_dump($postData);
                $cat_id = (isset($postData['cat_id']) && !empty($postData['cat_id'])) ? $postData['cat_id'] : '';
                $orderDetails = $this->productSubCategoryModel->getSubCategoryByCatId($cat_id);

                $this->getResponse()->setStatus(true);
                $this->getResponse()->setCode(200);
                $this->getResponse()->setData($orderDetails);
                $this->getResponse()->setShiftArray(false);
                echo $this->getResponse()->getResponseData();
                die();
            } else {
                $this->getResponse()->setStatus(true);
                $this->getResponse()->setCode(405);
                $this->getResponse()->setData([]);
                $this->getResponse()->setShiftArray(false);
                echo $this->getResponse()->getResponseData();
            }
        } catch (\Throwable $th) {
            $this->getResponse()->setStatus(false);
            $this->getResponse()->setCode(200);
            $this->getResponse()->setData([]);
            $this->getResponse()->setShiftArray(false);
            $this->getResponse()->setMsg($th->getMessage());
            echo $this->getResponse()->getResponseData();
            die();
        }
    }
}
