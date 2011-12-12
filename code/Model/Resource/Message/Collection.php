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

class Danslo_GooglePlusFeed_Model_Resource_Message_Collection
    extends Varien_Data_Collection
{

    protected $_client = null;
    protected $_userId = null;
    protected $_apiKey = null;

    protected $_title         = '';
    protected $_nextPageToken = '';

    public function __construct() {
        $this->_client = new Zend_Http_Client();
    }

    public function loadData($printQuery = false, $logQuery = false) {
        if(!$this->_userId) {
            Mage::throwException(Mage::helper('googleplusfeed')->__('Please specify a User ID.'));
        }
        if(!$this->_apiKey) {
            Mage::throwException(Mage::helper('googleplusfeed')->__('Please specify an API key.'));
        }

        $this->_getClient()->setUri(sprintf('%s/people/%s/activities/public',
            Mage::getStoreConfig('googleplusfeed/api_url'),
            $this->_userId
        ));
        $this->_getClient()->setParameterGet('key', $this->_apiKey);

        $this->_getActivities();
    }

    public function setUserId($userId) {
        $this->_userId = $userId;
        return $this;
    }

    public function setApiKey($apiKey) {
        $this->_apiKey = $apiKey;
        return $this;
    }

    protected function _setNextPageToken($nextPageToken) {
        $this->_nextPageToken = $nextPageToken;
        return $this;
    }

    protected function _setTitle($title) {
        $this->_title = $title;
    }

    public function getTitle() {
        return $this->_title;
    }

    protected function _getActivities() {
        $data = Mage::helper('core')->jsonDecode($this->_getClient()->request()->getBody());

        if(isset($data['error'])) {
            Mage::throwException($data['error']['message']);
        }

        $this->_setNextPageToken($data['nextPageToken']);
        $this->_setTitle($data['title']);

        foreach($data['items'] as $item) {
            $this->addItem(Mage::getModel('googleplusfeed/message')->setData($item));
        }
    }

    protected function _getClient() {
        return $this->_client;
    }

    public function setMaxResults($maxResults = 20) {
        $this->_getClient()->setParameterGet('maxResults', $maxResults);
        return $this;
    }

}
