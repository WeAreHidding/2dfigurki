<?php
/**
 * Essive
 */
class Figures_Api_Model_Etsy_Connector extends Figures_Cms_Model_Abstract
{
    /**
     * @return mixed
     */
    public function getMethods()
    {
        $jsonMethods = file_get_contents(Mage::getBaseDir('media') . DS . 'api/etsy_methods.json');

        return json_decode($jsonMethods, true);
    }

    /**
     * @param $methodName
     * @param $params
     *
     * @return mixed
     */
    public function call($methodName, $params)
    {
        $credentials = $this->_getOauth()->getCredentials();
        $methods = $this->getMethods();
        $method = $methods[$methodName];

        $this->_prepareParams($params);
        $url = $this->_buildUrl($method['uri'], $params);
        if (empty($params) || !$params) {
            $params = null;
        }

//        if ($method['visibility'] === 'private') {
            try {
                $oauth = new OAuth($credentials['etsy_consumer_key'], $credentials['etsy_consumer_secret'], OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
                $oauth->disableSSLChecks();
                $oauth->setToken($credentials['etsy_oauth_token'], $credentials['etsy_oauth_token_secret']);

                $oauth->fetch($url, $params, OAUTH_HTTP_METHOD_GET);
                $json = $oauth->getLastResponse();

                return json_decode($json, true);
            } catch (OAuthException $e) {
                var_dump($e->getMessage());
                exit;
            }
//        } else {
//            try {
//                $oauth = new OAuth($credentials['etsy_consumer_key'], $credentials['etsy_consumer_secret'], OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
//                $oauth->disableSSLChecks();
//
//                $oauth->fetch($url, $params, OAUTH_HTTP_METHOD_GET);
//                $json = $oauth->getLastResponse();
//
//                return json_decode($json, true);
//            } catch (OAuthException $e) {
//                var_dump($e->getMessage());
//                exit;
//            }
//        }
    }

    /**
     * @param $params
     */
    protected function _prepareParams(&$params)
    {
        foreach ($params as $key => $param) {
            if (empty($param)) {
                unset($params[$key]);
            }
        }
    }

    /**
     * @param $uri
     * @param $params
     *
     * @return string
     */
    protected function _buildUrl($uri, &$params)
    {
        foreach ($params as $key => $param) {
            if (strpos($uri, $key) !== false) {
                $uri = str_replace(':' . $key, $param, $uri);
                unset($params[$key]);
            }
        }

        return 'https://openapi.etsy.com/v2' . $uri;
    }

    /**
     * @return Figures_Api_Model_Etsy_Oauth
     */
    protected function _getOauth()
    {
        return Mage::getModel('figures_api/etsy_oauth');
    }
}