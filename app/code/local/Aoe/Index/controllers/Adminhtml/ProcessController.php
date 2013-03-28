<?php
require("app/code/core/Mage/Index/controllers/Adminhtml/ProcessController.php");

class Aoe_Index_Adminhtml_ProcessController extends Mage_Index_Adminhtml_ProcessController
{

    public function queueProcessAction()
    {
        $process = $this->_initProcess();
        if ($process) {
            Mage::getModel('cron/schedule') /* @var Aoe_Scheduler_Model_Schedule */
                ->setJobCode(Aoe_Index_Model_Observer::CRON_PREFIX.'_'.$process->getIndexerCode())
                ->schedule()
                ->save();

            $this->_getSession()->addSuccess(
                Mage::helper('index')->__('%s index was queued for reindexing.', $process->getIndexer()->getName())
            );
        } else {
            $this->_getSession()->addError(
                Mage::helper('index')->__('Cannot initialize the indexer process.')
            );
        }

        $this->_redirect('*/*/list');
    }

    public function massQueueAction()
    {
        /* @var $indexer Mage_Index_Model_Indexer */
        $indexer    = Mage::getSingleton('index/indexer');
        $processIds = $this->getRequest()->getParam('process');
        if (empty($processIds) || !is_array($processIds)) {
            $this->_getSession()->addError(Mage::helper('index')->__('Please select Indexes'));
        } else {
            try {
                foreach ($processIds as $processId) {
                    $process = $this->_initProcess($processId);
                    if ($process) {
                        Mage::getModel('cron/schedule') /* @var Aoe_Scheduler_Model_Schedule */
                            ->setJobCode(Aoe_Index_Model_Observer::CRON_PREFIX.'_'.$process->getIndexerCode())
                            ->schedule()
                            ->save();
                        $this->_getSession()->addSuccess(
                            Mage::helper('index')->__('%s index was queued for reindexing.', $process->getIndexer()->getName())
                        );
                    }
                }
                $count = count($processIds);
                $this->_getSession()->addSuccess(
                    Mage::helper('index')->__('Total of %d index(es) have been queued for reindexing.', $count)
                );
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addException($e, Mage::helper('index')->__('Cannot initialize the indexer process.'));
            }
        }

        $this->_redirect('*/*/list');
    }


    protected function _initProcess($processId = null)
    {
        $processId = $processId ? $processId : $this->getRequest()->getParam('process');
        if ($processId) {
            $process = Mage::getModel('index/process')->load($processId);
            if (! $process->getId()) {
                return false;
            }
        } else {
            return false;
        }

        try {
            $cronExpr = sprintf(Aoe_Index_Model_Observer::CRON_STRING_PATH, $process->getIndexerCode());
            Mage::getModel('core/config_data')
                ->load($cronExpr, 'path')
                ->setValue('')
                ->setPath($cronExpr)
                ->save();

            $model = sprintf(Aoe_Index_Model_Observer::CRON_MODEL_PATH, $process->getIndexerCode());
            Mage::getModel('core/config_data')
                ->load($model, 'path')
                ->setValue(Aoe_Index_Model_Observer::CRON_MODEL_EXPR)
                ->setPath($model)
                ->save();

            Mage::getConfig()->cleanCache();
        }
        catch (Exception $e) {
            Mage::throwException(Mage::helper('adminhtml')->__('Unable to save the cron expression.'));
        }

        return $process;
    }


}
