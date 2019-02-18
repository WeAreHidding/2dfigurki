<?php
/**
 * Essive
 */
class Figures_Api_Model_Etsy_Oauth extends Figures_Cms_Model_Abstract
{
    /**
     * @var Varien_Db_Adapter_Pdo_Mysql
     */
    protected $_connection;

    protected $_credentials = [];

    protected $_configPaths = [
        'etsy_consumer_key',
        'etsy_consumer_secret',
        'etsy_oauth_token',
        'etsy_oauth_token_secret'
    ];

    protected $_permissions = [
        'email_r',
        'listings_r',
        'listings_w',
        'listings_d',
        'transactions_r',
        'transactions_w',
        'billing_r',
        'profile_r',
        'profile_w',
        'address_r',
        'address_w',
        'favorites_rw',
        'shops_rw',
        'cart_rw',
        'recommend_rw',
        'feedback_r',
        'treasury_r',
        'treasury_w',
    ];

    /**
     * Figures_Api_Model_Oauth constructor.
     */
    public function __construct()
    {
        $this->_connection = $this->_getConnection();
        $this->_credentials = [
            'etsy_consumer_key' => $this->_connection->fetchOne($this->_connection->select()->from('core_config_data', 'value')->where('path = ?', 'etsy_consumer_key')),
            'etsy_consumer_secret' => $this->_connection->fetchOne($this->_connection->select()->from('core_config_data', 'value')->where('path = ?', 'etsy_consumer_secret'))
        ];
        parent::__construct();
    }

    /**
     * @return array|string
     * @throws Zend_Db_Adapter_Exception
     */
    public function request()
    {
        try {
            $oauth = new OAuth($this->_credentials['etsy_consumer_key'], $this->_credentials['etsy_consumer_secret']);
            $oauth->disableSSLChecks();
            $req_token = $oauth->getRequestToken($this->_preparePermissionUrl(), 'http://local.2df.com/index.php/admin/etsy/callback');
            $loginUrl = sprintf(
                "%s?oauth_consumer_key=%s&oauth_token=%s",
                $req_token['login_url'],
                $req_token['oauth_consumer_key'],
                $req_token['oauth_token']
            );

            $requestSecret = $req_token['oauth_token_secret'];
            $this->_connection->update(
                'core_config_data',
                [
                    'value'   => $requestSecret
                ],
                "path = 'etsy_last_oauth_token_secret'"
            );
        } catch (OAuthException $e) {
            return ['error' => true, 'message' => $e->getMessage()];
        }

        return $loginUrl;
    }

    /**
     * @return array|string
     * @throws Zend_Db_Adapter_Exception
     */
    public function setTokens()
    {
        $request_token = $_GET['oauth_token'];
        $request_token_secret = $this->_connection->fetchOne($this->_connection->select()->from('core_config_data', 'value')->where('path = ?', 'etsy_last_oauth_token_secret'));
        $verifier = $_GET['oauth_verifier'];

        try {
            $oauth = new OAuth($this->_credentials['etsy_consumer_key'], $this->_credentials['etsy_consumer_secret']);
            $oauth->disableSSLChecks();
            $oauth->setToken($request_token, $request_token_secret);
            $acc_token = $oauth->getAccessToken("https://openapi.etsy.com/v2/oauth/access_token", null, $verifier);
        } catch (OAuthException $e) {
            return ['error' => true, 'message' => $e->getMessage()];
        }

        if (!empty($acc_token['oauth_token']) && !empty($acc_token['oauth_token_secret'])) {
            $this->_connection->update(
                'core_config_data',
                [
                    'value'   => $acc_token['oauth_token']
                ],
                "path = 'etsy_oauth_token'"
            );
            $this->_connection->update(
                'core_config_data',
                [
                    'value'   => $acc_token['oauth_token_secret']
                ],
                "path = 'etsy_oauth_token_secret'"
            );
        } else {
            return ['error' => true, 'message' => 'no creds from API'];
        }

        return 'ok';
    }

    /**
     * @return bool
     */
    public function isConfigured()
    {
        return !empty($this->_credentials['etsy_consumer_key']) && !empty($this->_credentials['etsy_consumer_secret']);
    }

    /**
     * @return array
     */
    public function getCredentials()
    {
        return $this->_connection->fetchPairs(
            $this->_connection->select()
                ->from('core_config_data', ['path', 'value'])
                ->where('path IN (?)', $this->_configPaths)
        );
    }

    protected function _preparePermissionUrl()
    {
        $url = 'https://openapi.etsy.com/v2/oauth/request_token?scope=';
        $lastElement = end($this->_permissions);
        foreach ($this->_permissions as $permission) {
            if (!($lastElement == $permission)) {
                $url .= $permission . '%20';
            } else {
                $url .= $permission;
            }
        }

        return $url;
    }

    /**
     * @return Varien_Db_Adapter_Pdo_Mysql
     */
    protected function _getConnection()
    {
        return Mage::getModel('core/resource')->getConnection('core_write');
    }
}