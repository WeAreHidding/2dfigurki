<?php
/**
 * Essive
 */
class Figures_Api_Model_Etsy_Creator extends Mage_Core_Model_Abstract
{
    protected $_localStorageForImages;

    protected $_allowedParams = [
        'title'                => 'c',
        'description'          => 'c',
        'price'                => 'c',
        'quantity'             => 'c',
        'sku'                  => 'u',
        'materials'            => 'c',
        'tags'                 => 'c',
        'is_supply'            => 'c',
        'image'                => 'u',
        'who_made'             => 'c',
        'when_made'            => 'c',
        'shipping_template_id' => 'c',
        'state'                => 'c',
        'taxonomy_id'          => 'c'
    ];

    protected $_success = [];
    protected $_notice  = [];
    protected $_errors  = [];

    public function create($rows)
    {
        $this->_prepare($rows);
        if ($this->_errors) {
            return $this->_getReportHtml();
        }

        foreach ($rows as $number => $row) {
            $number = $number + 1;
            $image = $row['image']; unset($row['image']);
            $sku   = $row['sku']; unset($row['sku']);
            $report = $this->_getConnector()->call('createListing', $row, 'POST');
            if (!empty($report['error'])) {
                $this->_errors[] = "Row #$number : Etsy API error, import was stopped. Message: " . $report['error'];
                break;
            }

            if (!$listingId = $report['results'][0]['listing_id']) {
                $this->_errors[] = "Row #$number : Etsy API error, import was stopped. Message: cannot request listing id for created product.";
                break;
            }

            if ($image) {
                $report = $this->_getConnector()->loadImage($listingId, $image);
                if (!empty($report['error'])) {
                    $this->_errors[] = "Row #$number: Etsy API error, import was stopped. Message: " . $report['error'];
                    break;
                }
            }

            if ($sku) {
                $report = $this->_getConnector()->callUpdate($listingId, ['sku' => $sku]);
                if (!empty($report['error'])) {
                    $this->_errors[] = "Row #$number: Etsy API error, import was stopped. Message: " . $report['error'];
                    break;
                }
            }

            $this->_success[] = "Row #$number imported successfully";
        }

        return $this->_getReportHtml();
    }

    protected function _prepare(&$rows)
    {
        foreach ($rows as $k => $row) {
            //validator
            foreach ($this->_allowedParams as $code => $option) {
                if ($option == 'c' && !in_array($code, array_keys($row))) {
                    $this->_errors[] = "Missing required attribute: $code";
                    break;
                }
            }
            if ($diff = array_diff(array_keys($row), array_keys($this->_allowedParams))) {
                $diff = implode(',', $diff);
                $this->_errors[] = "Unknown columns ($diff), please clear them";
                break;
            }
            //endvalidator

            if (!empty($row['image'])) {
                $rows[$k]['image'] = $this->_downloadImage($row['image']);
            }
        }
    }

    protected function _downloadImage($imageLink)
    {
        $extension = pathinfo($imageLink); $extension = $extension['extension'];
        $localFile = $this->_getLocalFolderForImages() . uniqid() . '.' . $extension;
        file_put_contents($localFile, file_get_contents($imageLink));

        return $localFile;
    }

    protected function _getLocalFolderForImages()
    {
        if (!$this->_localStorageForImages) {
            $this->_localStorageForImages = Mage::getBaseDir('media') . DS . 'api/etsy_upload/images_upload/';
            if (!is_dir($this->_localStorageForImages)) {
                mkdir($this->_localStorageForImages, 0777, true);
            }
        }

        return $this->_localStorageForImages;
    }

    protected function _getReportHtml()
    {
        $html = '';
        if ($this->_errors) {
            $html .= '<p style="color:red; font-weight:700;">Import failed:</p>';
            foreach ($this->_errors as $error) {
                $html .= '<p style="color:red; margin:0">' . $error . '</p>';
            }
        }

        if ($this->_success) {
            $html .= '<p style="color:green; font-weight:700;">Import OK:</p>';
            foreach ($this->_success as $success) {
                $html .= '<p style="color:green; margin:0">' . $success . '</p>';
            }
        }

        if ($this->_notice) {
            $html .= '<p style="color:yellow; font-weight:700;">Notices:</p>';
            foreach ($this->_notice as $notice) {
                $html .= '<p style="color:yellow; margin:0">' . $notice . '</p>';
            }
        }

        return $html;
    }

    /**
     * @return Figures_Api_Model_Etsy_Connector
     */
    protected function _getConnector()
    {
        return Mage::getModel('figures_api/etsy_connector');
    }
}