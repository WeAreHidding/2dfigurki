<?php
require __DIR__ . '/abstract.php';

class Figures_Scheduler extends Mage_Shell_Abstract
{
    public function run()
    {
        $this->_updatePercents();
    }

    protected function _updatePercents()
    {
        /** @var Figures_Artist_Model_Cron $model */
        $model = Mage::getModel('figures_artist/cron');
        $model->calculateComissions();
    }
}

$shell = new Figures_Scheduler();
$shell->run();