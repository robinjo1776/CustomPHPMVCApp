<?php
class Response
{
    /**
     * [$_msg description]
     * @var [type]
     */
    private $_msg;
    private $_status = false;
    private $_code = 400;
    private $_mcode = '';
    private $_mmsg = '';
    private $_data = [];
    private $_content_type = "application/json";
    private $_response = [];
    private $_data_multi_array = true;
    private $_only_list_value = false;
    private $_shift_array = true;

    public function __construct()
    {
        $this->_response = [
            'msg' => $this->_msg,
            'status' => $this->_status,
            'code' => $this->_code,
            'data' => $this->_data,
            'mcode' => $this->_mcode,
            'mmsg' => $this->_mmsg,
        ];
    }

    public function setMsg($msg)
    {
        $this->_msg = $msg;
    }

    public function setStatus($status)
    {
        $this->_status = $status;
    }

    public function setCode($code)
    {
        $this->_code = $code;
    }

    public function setData($data)
    {
        $this->_data = $data;
    }

    public function setContentType($contentType)
    {
        $this->_content_type = $contentType;
    }

    public function setMethodCode($mcode)
    {
        $this->_mcode = $mcode;
    }

    public function getMethodCode()
    {
        return $this->_mcode;
    }

    public function setMethodMsg($mmsg)
    {
        $this->_mmsg = $mmsg;
    }

    public function setDataMultiArray($data_multi_array)
    {
        $this->_data_multi_array = $data_multi_array;
    }

    public function setOnlyListArray($_only_list_value)
    {
        $this->_only_list_value = $_only_list_value;
    }

    public function setShiftArray($_shift_array)
    {
        $this->_shift_array = $_shift_array;
    }

    private function setResponseData()
    {
        $this->_response = [
            'msg' => $this->_msg,
            'status' => $this->_status,
            'code' => $this->_code,
            'mcode' => $this->_mcode,
            'mmsg' => $this->_mmsg,
            'data' => [],
        ];
        $this->_data = is_array($this->_data) ? $this->_data : (array) $this->_data;
        if ($this->_data_multi_array) {
            $this->_response['data'] = $this->_data;
        } else if ($this->_only_list_value) {
            $this->_response = $this->_data;
        } else {
            $this->_response = array_merge($this->_response, $this->_data);
        }
    }

    public function getData()
    {
        return $this->_data;
    }

    private function getStatusMessage()
    {
        $this->_msg = !empty(Helper::$API_CODE[$this->_code]) ? Helper::$API_CODE[$this->_code] : Helper::$API_CODE[500];
        return $this->_msg;
    }

    private function getMethodStatusMessage()
    {
        $this->_mmsg = !empty(Helper::$METHOD_CODE[$this->_mcode]) ? Helper::$METHOD_CODE[$this->_mcode] : '';
        return $this->_mmsg;
    }

    private function setHeaders()
    {
        header("HTTP/1.1 " . $this->_code . " " . $this->getStatusMessage());
        header("Content-Type:" . $this->_content_type);
    }

    public function getResponseData()
    {
        $this->setHeaders();
        // $this->getMethodStatusMessage();
        $this->setResponseData();
        if ($this->_shift_array)
            return json_encode([$this->_response]);
        else
            return json_encode($this->_response);
    }
}
