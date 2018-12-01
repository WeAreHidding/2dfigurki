<?php

class Figures_Artist_Adminhtml_Artist_MassactionsController extends Mage_Adminhtml_Controller_Action
{
    protected $writer = null;

    public function massDeleteAction()
    {
        $this->_getArtistModel()->deleteDesigns($this->getRequest()->getParam('id'));
        $this->_redirectReferer($this->getUrl('adminhtml/artist_workshop/index'));
    }

    /**
     * @throws Exception
     */
    public function massExportAction()
    {
        $path = Mage::getBaseDir('media') . '/workshop/';
        $csvSavePath = $path . 'export/designs.csv';
        $imagesSavePath = $path . 'export/images/';
        if (!is_dir($imagesSavePath)) {
            mkdir($imagesSavePath, 0777, true);
        } else {
            rmdir($imagesSavePath);
            mkdir($imagesSavePath, 0777, true);
        }
        $connection = $this->_getConnection();
        $designData = $connection->fetchAll(
            $connection->select()->from('artist_work')
                ->where("id IN(" . implode(',', $this->getRequest()->getParam('id')) . ')')
                ->where('created_products_qty = 0')
        );

        foreach ($designData as $designItem) {
            $imagePath = $path . 'user_images/' . $designItem['customer_id'] . $designItem['image_path'];
            preg_match("/[^\/]+$/", $designItem['image_path'], $matches);
            $designItem['image_name'] = $designItem['id'] . '_' . $matches[0];
            $designItem['image_url']  = Mage::getBaseUrl('media') . 'workshop/user_images/' . $designItem['customer_id'] . $designItem['image_path'];
            unset($designItem['image_path']);
            copy(
                $imagePath,
                $imagesSavePath . $designItem['image_name']
                );
            $formCatsNames = [];
            foreach (explode(',', $designItem['proposed_form_category']) as $formCategoryId) {
                $category = Mage::getModel('catalog/category')->load($formCategoryId);
                $formCatsNames[] = $category->getName();
            }
            $row = [
                'Design ID' => $designItem['id'],
                'Customer ID' => $designItem['customer_id'],
                'Designer NickName' => $designItem['artist_name'],
                'Design Name' => $designItem['char_name'],
                'Design Description' => $designItem['description'],
                'Main Tag' => $designItem['main_tag'],
                'Tags' => $designItem['tags'],
                'Created' => $designItem['created_at'],
                'Image Name' => $designItem['image_name'],
                'Image Url' => $designItem['image_url'],
                'Form Categories' => implode(',', $formCatsNames),
            ];
            $this->getWriter($csvSavePath)->writeRow($row);
        }

        $filename = $this->_createArchieve($path);
        $this->_prepareDownloadResponse('export_' . date('Y-m-d') . '.zip', array('type' => 'filename', 'value' => $filename));
    }

    protected function _createArchieve($path)
    {
        $fileName = $path . '/export_' . date('Y-m-d') . '.zip';
        $zip = new ZipArchive();
        $zip->open($fileName, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        /** @var SplFileInfo[] $files */
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path . 'export'),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file)
        {
            if (!$file->isDir())
            {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($path . 'export') + 1);

                $zip->addFile($filePath, $relativePath);
            }
        }

        return $fileName;
    }

    /**
     * @param string $filename
     * @return Mage_ImportExport_Model_Export_Adapter_Csv
     * @throws Exception
     */
    public function getWriter($filename = '')
    {
        if (is_null($this->writer)) {
            $this->writer = new Mage_ImportExport_Model_Export_Adapter_Csv($filename);
        }

        return $this->writer;
    }

    /**
     * @return Figures_Artist_Model_Artist
     */
    protected function _getArtistModel()
    {
        return Mage::getModel('figures_artist/artist');
    }

    /**
     * @return Varien_Db_Adapter_Pdo_Mysql
     */
    protected function _getConnection()
    {
        return Mage::getModel('core/resource')->getConnection('core_write');
    }
}