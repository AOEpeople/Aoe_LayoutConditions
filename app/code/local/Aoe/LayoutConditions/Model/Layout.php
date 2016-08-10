<?php

/**
 * Class Aoe_LayoutConditions_Model_Layout
 *
 * @category Model
 * @package  Aoe_LayoutConditions
 * @author   AOE Magento Team <team-magento@aoe.com>
 * @license  Open Software License v. 3.0 (OSL-3.0)
 * @link     https://github.com/AOEpeople/Aoe_LayoutConditions
 */
class Aoe_LayoutConditions_Model_Layout extends Mage_Core_Model_Layout
{

    /**
     * Create layout blocks hierarchy from layout xml configuration
     *
     * @param Mage_Core_Layout_Element|null $parent Parent layout element
     * @return void
     */
    public function generateBlocks($parent = null)
    {

        if (empty($parent)) {
            $parent = $this->getNode();
        }

        if (isset($parent['ifconfig']) && ($configPath = (string) $parent['ifconfig'])) {
            if (!Mage::getStoreConfigFlag($configPath)) {
                return;
            }
        }

        if (isset($parent['unlessconfig']) && ($configPath = (string) $parent['unlessconfig'])) {
            if (Mage::getStoreConfigFlag($configPath)) {
                return;
            }
        }

        if (isset($parent['ifhelper']) && ($helperCallback = (string) $parent['ifhelper'])) {
            if (!$this->_checkIfHelper($helperCallback)) {
                return;
            }
        }

        parent::generateBlocks($parent);
    }

    /**
     * Add block object to layout based on xml node data
     *
     * @param Varien_Simplexml_Element $node   Node element
     * @param Varien_Simplexml_Element $parent Parent element
     * @return Mage_Core_Model_Layout
     */
    protected function _generateBlock($node, $parent)
    {
        if (isset($node['ifconfig']) && ($configPath = (string) $node['ifconfig'])) {
            if (!Mage::getStoreConfigFlag($configPath)) {
                return;
            }
        }

        if (isset($node['unlessconfig']) && ($configPath = (string) $node['unlessconfig'])) {
            if (Mage::getStoreConfigFlag($configPath)) {
                return;
            }
        }

        if (isset($node['ifhelper']) && ($helperCallback = (string) $node['ifhelper'])) {
            if (!$this->_checkIfHelper($helperCallback)) {
                return;
            }
        }

        return parent::_generateBlock($node, $parent);
    }

    /**
     * Enter description here...
     *
     * @param Varien_Simplexml_Element $node   Node element
     * @param Varien_Simplexml_Element $parent Parent element
     * @return Mage_Core_Model_Layout
     */
    protected function _generateAction($node, $parent)
    {
        if (isset($node['unlessconfig']) && ($configPath = (string) $node['unlessconfig'])) {
            if (Mage::getStoreConfigFlag($configPath)) {
                return $this;
            }
        }

        if (isset($node['ifhelper']) && ($helperCallback = (string) $node['ifhelper'])) {
            if (!$this->_checkIfHelper($helperCallback)) {
                return $this;
            }
        }

        return parent::_generateAction($node, $parent);
    }

    /**
     * @param string $helperCallback config from layout.xml for model callback
     * @throws Mage_Core_Exception
     * @return bool
     */
    protected function _checkIfHelper($helperCallback)
    {
        if (!preg_match(Mage_Cron_Model_Observer::REGEX_RUN_MODEL, $helperCallback, $run)) {
            Mage::throwException(Mage::helper('cron')->__('Invalid helper/method definition, expecting "helper/class::method".'));
        }
        if (!($helper = Mage::helper($run[1])) || !method_exists($helper, $run[2])) {
            Mage::throwException(Mage::helper('cron')->__('Invalid callback: %s::%s does not exist', $run[1], $run[2]));
        }
        $callback = [$helper, $run[2]];

        if (empty($callback)) {
            Mage::throwException(Mage::helper('cron')->__('No callbacks found'));
        }

        // @codingStandardsIgnoreStart
        $result = call_user_func($callback);
        // @codingStandardsIgnoreEnd

        return $result;
    }
}
