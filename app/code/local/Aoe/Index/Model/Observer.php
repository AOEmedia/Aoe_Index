<?php
/**
 * Aoe_Index
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the H&O Commercial License
 * that is bundled with this package in the file LICENSE_HO.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.h-o.nl/license
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@h-o.com so we can send you a copy immediately.
 *
 * @category    Aoe
 * @package     Aoe_Index
 * @copyright   Copyright © 2013 H&O (http://www.h-o.nl/)
 * @license     H&O Commercial License (http://www.h-o.nl/license)
 * @author      Paul Hachmang – H&O <info@h-o.nl>
 *
 * 
 */
 
class Aoe_Index_Model_Observer
{
    const CRON_STRING_PATH  = 'crontab/jobs/index_%s/schedule/cron_expr';
    const CRON_MODEL_PATH   = 'crontab/jobs/index_%s/run/model';
    const CRON_MODEL_EXPR   = 'aoe_index/observer::process';
    const CRON_PREFIX       = 'index';

    public function process($event)
    {
        $jobCode = str_replace(self::CRON_PREFIX.'_','',$event->getJobCode());

        /* @var $process Aoe_Index_Model_Process */
        $process = Mage::getModel('index/process')->load($jobCode, 'indexer_code');

        if ($process) {
            try {
                Varien_Profiler::start('__INDEX_PROCESS_REINDEX_ALL__');
                $process->reindexEverything();
                Varien_Profiler::stop('__INDEX_PROCESS_REINDEX_ALL__');

                return '';
            } catch (Mage_Core_Exception $e) {
                return 'ERROR: '.$e->getMessage();
            } catch (Exception $e) {
                return 'ERROR: '.$e;
            }
        } else {
            return 'ERROR: '.Mage::helper('index')->__('Cannot initialize the indexer process.');
        }
    }
}
