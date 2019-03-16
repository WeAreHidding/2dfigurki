<?php
// Include the Original Mage_Customer's AccountController.php file
// where the class 'Mage_Customer_AccountController' is defined
require_once Mage::getModuleDir('controllers', 'Mage_Customer').DS.'AccountController.php';

// Now extend the class 'Mage_Customer_AccountController'
class Figures_Customer_AccountController extends Mage_Customer_AccountController
{
    /**
     * Create customer account action
     */
    public function createPostAction()
    {
        $errUrl = $this->_getUrl('*/*/create', array('_secure' => true));

        if (!$this->_validateFormKey()) {
            $this->_redirectError($errUrl);
            return;
        }

        /** @var $session Mage_Customer_Model_Session */
        $session = $this->_getSession();
        if ($session->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }

        if (!$this->getRequest()->isPost()) {
            $this->_redirectError($errUrl);
            return;
        }

        $customer = $this->_getCustomer();

        try {
            $errors = $this->_getCustomerErrors($customer);

            if (empty($errors)) {
                $customer->cleanPasswordsValidationData();
                $customer->setPasswordCreatedAt(time());
                $customer->save();
//                if ($this->getRequest()->getPost('become_a_partner')) {
                    $customer->setGroupId(20);
                    $this->_getComissionModel()->setStartComission($customer->getId());
                    $this->_getMoneyModel()->setStartMoney($customer->getId());
                    $customer->save();
//                }
                $address = Mage::getModel("customer/address");
                $address->setCustomerId($customer->getId())
                    ->setFirstname($customer->getFirstname())
                    ->setLastname($customer->getLastname())
                    ->setTelephone($this->getRequest()->getPost('phone'));
                $address->save();
                $this->_dispatchRegisterSuccess($customer);
                $this->_successProcessRegistration($customer);
                return;
            } else {
                $this->_addSessionError($errors);
            }
        } catch (Mage_Core_Exception $e) {
            $session->setCustomerFormData($this->getRequest()->getPost());
            if ($e->getCode() === Mage_Customer_Model_Customer::EXCEPTION_EMAIL_EXISTS) {
                $url = $this->_getUrl('customer/account/forgotpassword');
                $message = $this->__('There is already an account with this email address. If you are sure that it is your email address, <a href="%s">click here</a> to get your password and access your account.', $url);
            } else {
                $message = $this->_escapeHtml($e->getMessage());
            }
            $session->addError($message);
        } catch (Exception $e) {
            $session->setCustomerFormData($this->getRequest()->getPost());
            $session->addException($e, $this->__('Cannot save the customer.'));
        }

        $this->_redirectError($errUrl);
    }

    /**
     * Default customer account page
     */
    public function indexAction()
    {
        $this->_redirect('customer_dashboard');
    }

    /**
     * @return Figures_Artist_Model_Comission
     */
    protected function _getComissionModel()
    {
        return Mage::getModel('figures_artist/comission');
    }

    /**
     * @return Figures_Artist_Model_Money
     */
    protected function _getMoneyModel()
    {
        return Mage::getModel('figures_artist/money');
    }
}