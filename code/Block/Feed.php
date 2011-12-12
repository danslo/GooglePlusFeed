<?php
/*
 * Copyright 2011 Daniel Sloof
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
*/

class Danslo_GooglePlusFeed_Block_Feed
    extends Mage_Core_Block_Template
    implements Mage_Widget_Block_Interface
{

    protected $_messageCollection = null;

    public function _construct() {
        $this->addData(array(
            'cache_lifetime' => Mage::getStoreConfig('googleplusfeed/cache_lifetime'),
            'cache_tags'     => array(Mage_Cms_Model_Block::CACHE_TAG),
            'cache_key'      => 'googleplusfeed'
        ));
    }

    public function getMessageCollection() {
        if(!$this->_messageCollection) {
            $this->_messageCollection = Mage::getModel('googleplusfeed/message')->getCollection()
                ->setApiKey($this->getApiKey())
                ->setUserId($this->getUserId());

            if($this->getMaxResults()) {
                $this->_messageCollection->setMaxResults($this->getMaxResults());
            }
        }
        return $this->_messageCollection;
    }

    protected function _beforeToHtml()
    {
        if(!$this->getTemplate()) {
            $this->setTemplate('googleplusfeed/feed.phtml');
        }
    }

}
