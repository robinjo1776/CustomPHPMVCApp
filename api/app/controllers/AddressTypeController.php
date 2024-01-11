<?php
class AddressTypeController extends Controller
{
    private $addressTypeModel;
    private $notificationModel;

    function __construct()
    {
        parent::__construct();
        Helper::AddModel("AddressTypeModel");
        Helper::AddModel("NotificationModel");
        $this->addressTypeModel = new AddressTypeModel();
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

    public function showAddressTypeDetails($addressTypeId)
    {
        try {
            $addressTypeDetails = $this->addressTypeModel->getAddressTypeDetailsById($addressTypeId);

            $this->getResponse()->setStatus(true);
            $this->getResponse()->setCode(200);
            $this->getResponse()->setData($addressTypeDetails);
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

    public function createAddressType()
    {
        try {
            if ($this->getRequest()->isPost()) {
                $postData = $this->getRequest()->getPostData();
                $result = $this->addressTypeModel->createAddressType($postData);

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
    public function updateAddressType()
    {
        try {
            if ($this->getRequest()->isPost()) {
                $postData = $this->getRequest()->getPostData();
                $result = $this->addressTypeModel->updateAddressType($postData);

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

    public function deleteAddressType($addressTypeId)
    {
        try {
            $result = $this->addressTypeModel->deleteAddressType($addressTypeId);

            if ($result['status']) {
                $option['message'] = "Address Type was deleted";
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
}
