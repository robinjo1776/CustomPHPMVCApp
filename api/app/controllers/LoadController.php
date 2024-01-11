<?php
class LoadController extends Controller
{
    private $loadModel;
    private $notificationModel;

    function __construct()
    {
        parent::__construct();
        Helper::AddModel("LoadModel");
        Helper::AddModel("NotificationModel");
        $this->loadModel = new LoadModel();
        $this->notificationModel = new NotificationModel();
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

    public function showLoadDetails($loadId)
    {
        try {
            $loadDetails = $this->loadModel->getLoadDetailsByLoadNo($loadId);

            $this->getResponse()->setStatus(true);
            $this->getResponse()->setCode(200);
            $this->getResponse()->setData($loadDetails);
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

    public function createLoad()
    {
        try {
            if ($this->getRequest()->isPost()) {
                $postData = $this->getRequest()->getPostData();
                $result = $this->loadModel->createLoad($postData);

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
    public function updateLoad()
    {
        try {
            if ($this->getRequest()->isPost()) {
                $postData = $this->getRequest()->getPostData();
                $result = $this->loadModel->updateLoad($postData);

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

    public function deleteLoad($loadId)
    {
        try {
            $result = $this->loadModel->deleteLoad($loadId);

            if ($result['status']) {
                $option['message'] = "Load was deleted";
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
