<?php
class CustomerController extends Controller
{
    private $customerModel;
    private $notificationModel;
    private $customerAddressModel;
    private $customerContactModel;

    function __construct()
    {
        parent::__construct();
        Helper::AddModel("CustomerModel");
        Helper::AddModel("NotificationModel");
        Helper::AddModel("CustomerAddressModel");
        Helper::AddModel("CustomerContactModel");
        $this->customerModel = new CustomerModel();
        $this->notificationModel = new NotificationModel();
        $this->customerAddressModel = new CustomerAddressModel();
        $this->customerContactModel = new CustomerContactModel();
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

    public function showCustomerAddresses()
    {
        try {
            if ($this->getRequest()->isPost()) {
                $postData = $this->getRequest()->getRawData();
                $customerId = (isset($postData['customerId']) && !empty($postData['customerId'])) ? $postData['customerId'] : '';
                $orderDetails = $this->customerModel->getCustomerAddressesByCustomerId($customerId);

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

    public function showCustomerDetails($customerId)
    {
        try {
            $customerDetails = $this->customerModel->getCustomerDetailsByCustomerId($customerId);
            $customerDetails['cust_addresses'] = $this->customerAddressModel->getCustomerAddressesByCustomerId($customerId);
            $customerDetails['cust_phones']  = $this->customerContactModel->getCustomerContactsByCustomerId($customerId);

            $this->getResponse()->setStatus(true);
            $this->getResponse()->setCode(200);
            $this->getResponse()->setData($customerDetails);
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

    public function createCustomer()
    {
        try {
            if ($this->getRequest()->isPost()) {
                $postData = $this->getRequest()->getPostData();
                // var_dump($postData);
                // var_dump($_POST);
                // var_dump($_GET);
                // var_dump($_REQUEST);
                // die();
                $result = $this->customerModel->createCustomer($postData);

                if (isset($postData['cust_addresses']) && !empty($postData['cust_addresses'])) {
                    $custAddrPostData = $postData['cust_addresses'];
                    foreach ($custAddrPostData as $item) {
                        $item['cus_id'] = $result['data']['id'];
                        $resultCusAddr = $this->customerAddressModel->createCustomerAddress($item);
                    }
                }

                if (isset($postData['cust_phones']) && !empty($postData['cust_phones'])) {
                    $custAddrPostData = $postData['cust_phones'];
                    foreach ($custAddrPostData as $item) {
                        $item['cus_id'] = $result['data']['id'];
                        $resultCusContact = $this->customerContactModel->createCustomerContact($item);
                    }
                }
                // var_dump($result);
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
    public function updateCustomer()
    {
        try {
            if ($this->getRequest()->isPost()) {
                $postData = $this->getRequest()->getPostData();
                $result = $this->customerModel->updateCustomer($postData);
                // var_dump($postData);
                // var_dump($result);

                if (isset($postData['cust_addresses']) && !empty($postData['cust_addresses'])) {
                    $custAddrPostData = $postData['cust_addresses'];
                    $this->customerAddressModel->deleteCustomerAddress($result['data']['id']);
                    foreach ($custAddrPostData as $item) {
                        $item['cus_id'] = $result['data']['id'];
                        $resultCusAddr = $this->customerAddressModel->createCustomerAddress($item);
                    }
                }

                if (isset($postData['cust_phones']) && !empty($postData['cust_phones'])) {
                    $custAddrPostData = $postData['cust_phones'];
                    $this->customerContactModel->deleteCustomerContact($result['data']['id']);
                    foreach ($custAddrPostData as $item) {
                        $item['cus_id'] = $result['data']['id'];
                        $resultCusContact = $this->customerContactModel->createCustomerContact($item);
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

    public function deleteCustomer($customerId)
    {
        try {
            $result = $this->customerModel->deleteCustomer($customerId);

            if ($result['status']) {
                $option['message'] = "Customer was deleted";
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

    public function importCustomer()
    {
        try {
            if ($this->getRequest()->isPost()) {
                $postData = $this->getRequest()->getRequestData();
                $filesData = $this->getRequest()->getFilesData();
                $result = $this->customerModel->uploadFile($postData, $filesData);
                // var_dump($result);
                if ($result['status']) {
                    $data = array_merge($result["data"], $postData);
                    $result = $this->customerModel->importCustomerData($data);
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
}
