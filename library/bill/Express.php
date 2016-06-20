<?php

namespace app\library\bill;

class Express
{
    public function getExpressInfo($expressCode)
    {
        $requestUrl = 'http://www.kuaidi100.com/query';
        $method = 'GET';
        $expressCompany = $this->_getExpressCompanyName($expressCode);
        if ($expressCompany !== false) {
            $param = array('type' => $expressCompany, 'postid' => $expressCode);
            $jsonData = Curl::sendRequestByCurl($requestUrl, $param, $method);
            $decodeData = json_decode($jsonData, true);
            if ($decodeData['message'] == 'ok') {
                return $decodeData['data'];
            }
        }

        return false;
    }

    private function _getExpressCompanyName($expressCode)
    {
        $requestUrl = 'http://www.kuaidi100.com/autonumber/auto';
        $param = array('num' => $expressCode);
        $method = Constant::HTTP_METHOD_GET;

        $jsonData = Curl::sendRequestByCurl($requestUrl, $param, $method);
        $decodeData = json_decode($jsonData, true);
        if (!empty($decodeData)) {
            return $decodeData[0]['comCode'];
        }

        return false;
    }
} 