<?php
class OrderController extends Controller
{
    private $orderModel;
    private $notificationModel;
    private $orderItemModel;

    function __construct()
    {
        parent::__construct();
        Helper::AddModel("OrderModel");
        Helper::AddModel("NotificationModel");
        Helper::AddModel("OrderItemModel");
        $this->orderModel = new OrderModel();
        $this->notificationModel = new NotificationModel();
        $this->orderItemModel = new OrderItemModel();
    }

    public function index()
    {
        // Render view
        // $this->render('home/index', $data);
        $response = new Response();
        $this->getResponse()->setStatus(false);
        $this->getResponse()->setCode(400);
        $this->getResponse()->setData([]);
        $this->getResponse()->setShiftArray(false);
        echo $this->getResponse()->getResponseData();
        die();
    }

    public function showOrderDetails($orderId)
    {
        try {
            $orderDetails = $this->orderModel->getOrderDetailsByOrderNo($orderId);
            $orderDetails['product_item'] = $this->orderItemModel->getOrderItemsByOrderId($orderId);

            $this->getResponse()->setStatus(true);
            $this->getResponse()->setCode(200);
            $this->getResponse()->setData($orderDetails);
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

    public function createOrder()
    {
        try {
            if ($this->getRequest()->isPost()) {
                $postData = $this->getRequest()->getPostData();
                $result = $this->orderModel->createOrder($postData);
                // var_dump($postData);
                // die();

                if ($result['status'] == true && isset($postData['product_item']) && !empty($postData['product_item'])) {
                    $orderItemPostData = $postData['product_item'];
                    foreach ($orderItemPostData as $item) {
                        $item['orderid'] = $result['data']['orderno'];
                        $resultOrderItem = $this->orderItemModel->createOrderItem($item);
                    }
                }

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
    public function updateOrder()
    {
        try {
            if ($this->getRequest()->isPost()) {
                $postData = $this->getRequest()->getPostData();
                $result = $this->orderModel->updateOrder($postData);
                // var_dump($postData);
                // var_dump($result);

                if ($result['status'] == true && isset($postData['product_item']) && !empty($postData['product_item'])) {
                    $orderItemPostData = $postData['product_item'];
                    $this->orderItemModel->deleteOrderItem($result['data']['orderno']);
                    foreach ($orderItemPostData as $item) {
                        $item['orderid'] = $result['data']['orderno'];
                        $resultOrderItem = $this->orderItemModel->createOrderItem($item);
                        // var_dump($resultOrderItem);
                    }
                }

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

    public function deleteOrder($orderId)
    {
        try {
            $result = $this->orderModel->deleteOrder($orderId);

            if ($result['status']) {
                $option['message'] = "Order was deleted";
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

    public function invoiceUpdate($orderId)
    {
        try {
            // var_dump($orderId);
            $getData = $this->getRequest()->getGetData();
            $result = $this->orderModel->invoiceUpdate($orderId, $getData);

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
}
