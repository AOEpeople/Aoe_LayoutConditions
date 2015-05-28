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

        if (isset($parent['ifhelper']) && ($configPath = (string) $parent['ifhelper'])) {
            if (!Mage::helper($configPath)->checkLayoutCondition($parent->getBlockName())) {
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

        if (isset($node['ifhelper']) && ($configPath = (string) $node['ifhelper'])) {
            if (!Mage::helper($configPath)->checkLayoutCondition($parent->getBlockName())) {
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

        if (isset($node['ifhelper']) && ($configPath = (string) $node['ifhelper'])) {
            if (!Mage::helper($configPath)->checkLayoutCondition($parent->getBlockName())) {
                return;
            }
        }

        return parent::_generateAction($node, $parent);
    }
}
