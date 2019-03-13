<?php
class Figures_Artist_Helper_Csv extends Mage_Core_Helper_Abstract
{
    public $csvData;

    protected $_reader;

    public function arrayToCsv($array, $filePath)
    {
        $fp = fopen($filePath, 'w');

        array_unshift($array, array_keys($array[0]));
        foreach ($array as $fields) {
            fputcsv($fp, $fields);
        }

        fclose($fp);

        return $filePath;
    }

    public function csvToArray($filePath)
    {
        return $this->_loadCsv($filePath, true);
    }

    public function _loadCsv($filename = '', $withHeader = true)
    {
        $data = $this->_getReader()->getData($filename);

        $this->csvData = array();

        if ($withHeader) {
            $header = $data[0];
            unset($data[0]);
            foreach ($data as $row) {
                $row = array_combine($header, $row);
                $this->csvData[] = $row;
            }
        } else {
            $this->csvData = $data;
        }

        unset($data, $row, $header);
        array_walk_recursive($this->csvData, array($this, 'trimArrayValue'));

        return $this->csvData;
    }

    protected function _getReader()
    {
        if (is_null($this->_reader)) {
            $this->_reader = new Varien_File_Csv();
            $this->_reader->setDelimiter(";");
        }

        return $this->_reader;
    }
}