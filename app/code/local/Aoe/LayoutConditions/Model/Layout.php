<?php

class Aoe_LayoutConditions_Model_Layout extends Mage_Core_Model_Layout {

	/**
	 * Create layout blocks hierarchy from layout xml configuration
	 *
	 * @param Mage_Core_Layout_Element|null $parent
	 */
	public function generateBlocks($parent=null) {

		if (empty($parent)) {
			$parent = $this->getNode();
		}

		if (isset($parent['ifconfig']) && ($configPath = (string)$parent['ifconfig'])) {
			if (!Mage::getStoreConfigFlag($configPath)) {
				return;
			}
		}
		if (isset($parent['unlessconfig']) && ($configPath = (string)$parent['unlessconfig'])) {
			if (Mage::getStoreConfigFlag($configPath)) {
				return;
			}
		}
		parent::generateBlocks($parent);
	}

	/**
	 * Add block object to layout based on xml node data
	 *
	 * @param Varien_Simplexml_Element $node
	 * @param Varien_Simplexml_Element $parent
	 * @return Mage_Core_Model_Layout
	 */
	protected function _generateBlock($node, $parent) {
		if (isset($node['ifconfig']) && ($configPath = (string)$node['ifconfig'])) {
			if (!Mage::getStoreConfigFlag($configPath)) {
				return;
			}
		}
		if (isset($node['unlessconfig']) && ($configPath = (string)$node['unlessconfig'])) {
			if (Mage::getStoreConfigFlag($configPath)) {
				return;
			}
		}
		return parent::_generateBlock($node, $parent);
	}

	/**
	 * Enter description here...
	 *
	 * @param Varien_Simplexml_Element $node
	 * @param Varien_Simplexml_Element $parent
	 * @return Mage_Core_Model_Layout
	 */
	protected function _generateAction($node, $parent) {
		if (isset($node['unlessconfig']) && ($configPath = (string)$node['unlessconfig'])) {
			if (Mage::getStoreConfigFlag($configPath)) {
				return $this;
			}
		}
		return parent::_generateAction($node, $parent);
	}


}
