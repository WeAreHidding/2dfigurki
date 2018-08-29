<?php
/**
 * Essive
 */
class Figures_Routing_Controller_Router extends Mage_Core_Controller_Varien_Router_Standard
{
    /**
     * @param $observer
     */
    public function initControllerRouters($observer)
    {
        /* @var $front Mage_Core_Controller_Varien_Front */
        $front = $observer->getEvent()->getFront();

        $front->addRouter('figures_routing', $this);
    }

    /**
     * @param Zend_Controller_Request_Http $request
     * @return bool
     */
    public function match(Zend_Controller_Request_Http $request)
    {
        if (!Mage::isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect(Mage::getUrl('install'))
                ->sendResponse();
            exit;
        }

        $pathInfoInLowercase = strtolower($request->getPathInfo());

        if ($this->checkPartnerUrl($request, $pathInfoInLowercase)) {
            return true;
        }

        return false;
    }

    public function checkPartnerUrl($request, $pathInfoInLowercase)
    {
        if (preg_match('/new-artwork.html/', $pathInfoInLowercase, $matches)) {
            $request->setModuleName('figures_artist')
                ->setControllerName('workshop')
                ->setActionName('index');

            return true;
        }
    }
}