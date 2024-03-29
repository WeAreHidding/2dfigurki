<?php

class Figures_Dashboard_DashboardController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        if (!Mage::getSingleton('customer/session')->getCustomer()->getId()) {
            Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('customer/account'));
        }
        $this->loadLayout();
        $this->renderLayout();
    }

    public function saveInfoAction()
    {
        $model = $this->_getArtistModel();
        $params = $this->getRequest()->getParams();
        $proposedFormCategories = [];
        foreach ($params as $key => $value) {
            if ($key == 'ws_image') {
                continue;
            }
            if(stristr($key, 'form_cat_') !== FALSE) {
                $input = explode('form_cat_', $key);
                $proposedFormCategories[] = $input[1];
                continue;
            }
            $model->setData($key, $value);
        }

        $model->setData('proposed_form_category', implode(',', $proposedFormCategories));

        $customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();
        $model->setData('customer_id', $customerId);

        $type = 'ws_image';
        if (isset($_FILES[$type]['name']) && $_FILES[$type]['name'] != '') {
            try {
                $uploader = new Varien_File_Uploader($type);
                $uploader
                    ->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(true);
                $path = Mage::getBaseDir('media') . DS . 'workshop/user_images/' . $customerId . '/';
                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }
                $uploader->save($path, $_FILES[$type]['name']);
                $filename = $uploader->getUploadedFileName();
                $model->setData('image_path', $filename);
            } catch (Exception $e) {

            }
        }

        $model->save();
    }

    public function loadTabAction()
    {
        $tabName = $this->getRequest()->getParam('tab');
        $block = false;
        switch ($tabName) {
            case 'dash':
                $block = $this->getLayout()->createBlock('figures_dashboard/dashboard_dashboard')->setTemplate('dashboard/pages/dashboard.phtml');
                break;
            case 'design':
                $block = $this->getLayout()->createBlock('figures_dashboard/dashboard_design')->setTemplate('dashboard/pages/design.phtml');
                break;
            case 'design_management':
                $block = $this->getLayout()->createBlock('figures_dashboard/dashboard_designManagement')->setTemplate('dashboard/pages/design_management.phtml');
                break;
            case 'stats':
                $block = $this->getLayout()->createBlock('figures_dashboard/dashboard')->setTemplate('dashboard/pages/stats.phtml');
                break;
            case 'account':
                $block = $this->getLayout()->createBlock('figures_dashboard/dashboard_account')->setTemplate('dashboard/pages/account.phtml');
                break;
        }

        if (!$block) {
            return;
        }

        $html = $block->toHtml();

        $this->getResponse()->setBody($html);
    }

    public function getCreditDataAction()
    {//TODO: credit
        $params = $this->getRequest()->getParams();
        $bind = "artist_id = {$params['customer_id']} AND created_at >= '{$params['from']}' AND created_at <= '{$params['to']}'";
        if ($params['status'] != 'all') {
            $bind .=  " AND artist_comission_status = '{$params['status']}'";
        }

        $salesModel = $this->_getSalesModel();
        $salesData = $salesModel->getSales($bind, true);
        if (!$salesData) {
            $this->getResponse()->setBody('<div class="offset-4 col-4 col-auto">
                            <h6>No entries for the current period</h6>
                        </div>');
            return;
        }
        $html = '
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <td>Ordered at</td>
                        <td>Product Name</td>
                        <td>Product Price</td>
                        <td>Discount</td>
                        <td>Comission %</td>
                        <td>Your comission</td>
                        <td>Order status</td>
                        <td>Comission Status</td>
                    </tr>
                </thead>
                <tbody>
        ';
        foreach ($salesData as $item) {
            $html .= '
                    <tr>
                        <td>' . $item['created_at'] .'</td>
                        <td><a href="' . $item['product_url'] . '">' . $item['product_name'] .'</a></td>
                        <td>' . $item['price'] .'</td>
                        <td>' . $item['discount'] .'%</td>
                        <td>' . $item['artist_comission'] .'%</td>
                        <td>' . $item['artist_comission_net'] . ' USD</td>
                        <td>' . $item['order_status'] .'</td>
                        <td>' . $salesModel->getLabelForComissionStatus($item['artist_comission_status']) .'</td>
                    </tr>    
            ';
        }

        $html.='
                </tbody>
            </table>   ';

        $this->getResponse()->setBody($html);
    }

    public function getSalesDataAction()
    {
        $params = $this->getRequest()->getParams();
        $bind = "artist_id = {$params['customer_id']} AND created_at >= '{$params['from']}' AND created_at <= '{$params['to']}'";
        if ($params['status'] != 'all') {
            $bind .=  " AND artist_comission_status = '{$params['status']}'";
        }

        $salesModel = $this->_getSalesModel();
        $salesData = $salesModel->getSales($bind, true);
        if (!$salesData) {
            $this->getResponse()->setBody('<div class="offset-4 col-4 col-auto">
                            <h6>No entries for the current period</h6>
                        </div>');
            return;
        }
        $html = '
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <td>Ordered at</td>
                        <td>Product Name</td>
                        <td>Product Price</td>
                        <td>Discount</td>
                        <td>Comission %</td>
                        <td>Your comission</td>
                        <td>Order status</td>
                        <td>Comission Status</td>
                    </tr>
                </thead>
                <tbody>
        ';
        foreach ($salesData as $item) {
            $html .= '
                    <tr>
                        <td>' . $item['created_at'] .'</td>
                        <td><a href="' . $item['product_url'] . '">' . $item['product_name'] .'</a></td>
                        <td>' . $item['price'] .'</td>
                        <td>' . $item['discount'] .'%</td>
                        <td>' . $item['artist_comission'] .'%</td>
                        <td>' . $item['artist_comission_net'] . ' USD</td>
                        <td>' . $item['order_status'] .'</td>
                        <td>' . $salesModel->getLabelForComissionStatus($item['artist_comission_status']) .'</td>
                    </tr>    
            ';
        }

        $html.='
                </tbody>
            </table>   ';

        $this->getResponse()->setBody($html);
    }

    /**
     * @return Figures_Artist_Model_Sales
     */
    protected function _getSalesModel()
    {
        return Mage::getModel('figures_artist/sales');
    }

    /**
     * @return Figures_Artist_Model_Artist
     */
    protected function _getArtistModel()
    {
        return Mage::getModel('figures_artist/artist');
    }


    /*controllers for Dashboard/account*/

    /*public function changeEmailAction()
    {
        $response_array = [];
        header('Content-type: application/json');

        $params = $this->getRequest()->getParams();

        if (!filter_var($params["email"], FILTER_VALIDATE_EMAIL)) {
            $response_array['status'] = 'error';
            $response_array['title'] = __("ERROR");
            $response_array['message'] = __("Email address is not correct!");
            echo json_encode($response_array);
            return;
        }

        $customer = Mage::getSingleton('customer/session')->getCustomer();
        $id = $customer->getId();
        $collection = Mage::getModel('customer/customer')->getCollection();
        $collection->addAttributeToSelect('email');
        $collection->addFieldToFilter(array(
            array('attribute'=>'email','eq'=>$params["email"]),
        ));
        $collection->addFieldToFilter(array(
            array('attribute'=>'entity_id','neq'=>$id),
        ));

        $existingmails = $collection->getColumnValues('entity_id');

        if(!empty($existingmails)){
            $response_array['status'] = 'error';
            $response_array['title'] = __("ERROR");
            $response_array['message'] = __("Email address is already in use!");
            echo json_encode($response_array);
            return;
        }

        $data = array('email'=>$params["email"]);

        $model = Mage::getModel('customer/customer')->load($id)->addData($data);
        try {
            $model->setId($id)->save();
            $response_array['status'] = 'success';
            $response_array['title'] = __("SUCCESS");
            $response_array['message'] = __("Data updated successfully!");
            echo json_encode($response_array);

        } catch (Exception $e){
            echo $e->getMessage();
        }

        return;
    }*/

    public function changePasswordAction()
    {
        $response_array = [];
        header('Content-type: application/json');

        $params = $this->getRequest()->getParams();

        if(!preg_match("/^[a-zA-Z\d]{6,}$/", $params["acc_password"])){
            $response_array['status'] = 'error';
            $response_array['title'] = __("ERROR");
            $response_array['message'] = __("Please enter 6 or more characters without leading or trailing spaces!");
            echo json_encode($response_array);
            return;
        }

        if($params['acc_password']!==$params['repeat_acc_password']){
            $response_array['status'] = 'error';
            $response_array['title'] = __("ERROR");
            $response_array['message'] = __("Passwords doesn't match");
            echo json_encode($response_array);
            return;
        }

        $customer = Mage::getSingleton('customer/session')->getCustomer();
        $id = $customer->getId();

        try {
            $customer = Mage::getModel('customer/customer')->load($id);
            $customer->setPassword($params["acc_password"]);
            $customer->save();
            $response_array['status'] = 'success';
            $response_array['title'] = __("SUCCESS");
            $response_array['message'] = __('Your Password has been Changed Successfully');
            echo json_encode($response_array);
        }
        catch(Exception $e) {
            echo  'Error : '.$e->getMessage();
        }

        return;

    }


}