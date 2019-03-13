<?php
/**
 * Essive
 */
class Figures_Api_Model_Etsy_Connector extends Figures_Cms_Model_Abstract
{
    protected $_updateListingAttributes = ['title', 'description'];

    protected $_updateInventoryAttributes = ['sku', 'price', 'quantity'];

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
     * @param $httpMethod
     *
     * @return mixed
     */
    public function call($methodName, $params, $httpMethod = null)
    {
        $credentials = $this->_getOauth()->getCredentials();
        $methods = $this->getMethods();
        $method = $methods[$methodName];

        $this->_prepareParams($params);
        $url = $this->_buildUrl($method['uri'], $params);
        if (empty($params) || !$params) {
            $params = null;
        }

        try {
            $oauth = new OAuth($credentials['etsy_consumer_key'], $credentials['etsy_consumer_secret'], OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
            $oauth->disableSSLChecks();
            $oauth->setToken($credentials['etsy_oauth_token'], $credentials['etsy_oauth_token_secret']);
            if (!$httpMethod) {
                $httpMethod =  $method['http_method'];
            }

            if ($httpMethod == 'PUT') {
                $oauth->fetch($url, $params, OAUTH_HTTP_METHOD_PUT);
            } elseif ($httpMethod == 'POST') {
                $oauth->fetch($url, $params, OAUTH_HTTP_METHOD_POST);
            } else {
                $oauth->fetch($url, $params, OAUTH_HTTP_METHOD_GET);
            }
            $json = $oauth->getLastResponse();

            return json_decode($json, true);
        } catch (OAuthException $e) {
            return ['error' => $oauth->getLastResponse()];
        }
    }

    /**
     * @param $listingId
     * @param $params
     *
     * @return array
     */
    public function callUpdate($listingId, $params)
    {
        $needUpdateInventory = $needUpdateListing = false;
        $inventoryParams = $listingParams = [];
        $response = ['error' => '', 'ok' => ''];

        foreach ($params as $param => $value) {
            if (in_array($param, $this->_updateInventoryAttributes) && $value) {
                $needUpdateInventory = true;
                $inventoryParams[$param] = $value;
            } elseif (in_array($param, $this->_updateListingAttributes) && $value) {
                $needUpdateListing = true;
                $listingParams[$param] = $value;
                $listingParams['listing_id'] = $listingId;
            }
        }

        if (!$needUpdateInventory && !$needUpdateListing) {
            $response = ['error' => 'Missing params for response'];
        }

        if ($needUpdateInventory) {
            $productsJson = $this->_getUpdateInventoryLayout($listingId, $inventoryParams);
            if (!empty($productsJson['error'])) {
                $response['error'] .= 'ERROR when trying to get inventory data : ' . $productsJson['error'] . '<br>';
            } else {
                $responseInventory = $this->call('updateInventory', ['listing_id' => $listingId, 'products' => $productsJson], 'PUT');
                if (!empty($responseInventory['error'])) {
                    $response['error'] .= 'ERROR when trying to update inventory data : ' . $responseInventory['error'] . '<br>';
                } else {
                    $response['ok'] .= 'OK for inventory update, params - ' . implode(',', $inventoryParams) . '<br>';
                }
            }
        }
        if ($needUpdateListing) {
            $responseListing = $this->call('updateListing', $listingParams, 'PUT');
            if (!empty($responseListing['error'])) {
                $response['error'] .= 'ERROR when trying to update listing data : ' . $responseListing['error'] . '<br>';
            } else {
                $response['ok'] .= 'OK for listing update, params - ' . implode(',', $listingParams) . '<br>';
            }
        }

        return $response;
    }

    /**
     * @param $listingId
     * @param $inventoryParams
     * @return mixed|string
     */
    protected function _getUpdateInventoryLayout($listingId, $inventoryParams)
    {
        $response = $this->call('getInventory', ['listing_id' => $listingId]);

        if (!empty($response['error'])) {
            return $response;
        }

        if (!empty($inventoryParams['sku'])) {
            $sku = $inventoryParams['sku'];
        } else {
            $sku = $response['results']['products'][0]['sku'];
        }
        if (!empty($inventoryParams['price'])) {
            $price = (float)$inventoryParams['price'];
        } else {
            $price = (float)$response['results']['products'][0]['offerings'][0]['price']['currency_formatted_raw'];
        }
        if (!empty($inventoryParams['quantity'])) {
            $quantity = (int)$inventoryParams['quantity'];
        } else {
            $quantity = (int)$response['results']['products'][0]['offerings'][0]['quantity'];
        }

        $products = [
            [
                'property_values' => $response['results']['products'][0]['property_values'],
                'sku'             => $sku,
                'offerings'       => [
                    [
                        'price'      => $price,
                        'quantity'   => $quantity,
                        'is_enabled' => (int)$response['results']['products'][0]['offerings'][0]['is_enabled']
                    ]
                ]
            ]
        ];

        return json_encode($products);
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