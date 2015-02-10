<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This file is a part of Open Cloud API Project.
 *
 * Open Cloud API project tries to provide free APIs for internet apps
 * to fetch public structured data (such as country list) or some
 * common computing services (such as generating QR code).
 *
 * For more information, please refer to:
 *
 *		http://www.fullstackengineer.net/zh/project/open-cloud-api-zh
 *		http://www.fullstackengineer.net/en/project/open-cloud-api-en
 *
 * Copyright (C) 2015 WEI Yongming
 * <http://www.fullstackengineer.net/zh/engineer/weiyongming>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

class ZhConverter {

	const CACHE_VERSION_KEY = 'OCAPI-VERSION-1';

	public $mVariantNames;
	public $mTablesLoaded = false;
	public $mTables;
	public $mCacheKey;

	public $mCacheObj;
	public $mCacheGetFunc;
	public $mCacheSetFunc;

	function __construct ($params) {
		$this->mCacheObj = $params['cache_obj'];
		$this->mCacheGetFunc = $params['cache_get_func'];
		$this->mCacheSetFunc = $params['cache_set_func'];

		$this->mVariantNames = array(
			'zh' => '原文',
			'zh-hans' => '简体',
			'zh-hant' => '繁體',
			'zh-cn' => '大陆',
			'zh-tw' => '台灣',
			'zh-hk' => '香港',
			'zh-mo' => '澳門',
			'zh-sg' => '新加坡',
			'zh-my' => '大马',
		);

		$this->mCacheKey = 'zh-conversiontables';
	}
	/**
	 * Load default conversion tables.
	 * This method must be implemented in derived class.
	 *
	 * @private
	 * @throws MWException
	 */
	function loadDefaultTables() {
		require 'ZhConversion.php';
		$this->mTables = array (
			'zh-hans' => $zh2Hans,
			'zh-hant' => $zh2Hant,
			'zh-cn' => array_merge ($zh2Hans, $zh2CN),
			'zh-hk' => array_merge ($zh2Hant, $zh2HK),
			'zh-mo' => array_merge ($zh2Hant, $zh2HK),
			'zh-my' => array_merge ($zh2Hans, $zh2SG),
			'zh-sg' => array_merge ($zh2Hans, $zh2SG),
			'zh-tw' => array_merge ($zh2Hant, $zh2TW),
			'zh' => array (),
		);
	}

	/**
	 * Load conversion tables either from the cache or the disk.
	 * @private
	 * @param bool $fromCache Load from memcached? Defaults to true.
	 */
	function loadTables ($fromCache = true) {
		if ($this->mTablesLoaded) {
			return;
		}

		$this->mTablesLoaded = true;
		$this->mTables = false;
		if ($fromCache && $this->mCacheGetFunc) {
			$cache_get_func = $this->mCacheGetFunc;
			$this->mTables = $cache_get_func ($this->mCacheObj, $this->mCacheKey);
		}

		if (!$this->mTables || !array_key_exists (self::CACHE_VERSION_KEY, $this->mTables)) {
			// not in cache, or we need a fresh reload.
			// We will first load the default tables
			// then update them using things in MediaWiki:Conversiontable/*
			$this->loadDefaultTables ();
			/*
			foreach ($this->mVariants as $var) {
				$cached = $this->parseCachedTable ($var);
				$this->mTables[$var] = array_merge ($this->mTables[$var], $cached);
			}
			*/

			$this->postLoadTables ();
			$this->mTables[self::CACHE_VERSION_KEY] = true;

			if ($this->mCacheSetFunc) {
				$cache_set_func = $this->mCacheSetFunc;
				$cache_set_func ($this->mCacheObj, $this->mCacheKey, $this->mTables, 43200);
			}
		}
	}

	function postLoadTables () {
		$this->mTables['zh-cn'] = array_merge ($this->mTables['zh-cn'], $this->mTables['zh-hans']);
		$this->mTables['zh-hk'] = array_merge ($this->mTables['zh-hk'], $this->mTables['zh-hant']);
		$this->mTables['zh-mo'] = array_merge ($this->mTables['zh-mo'], $this->mTables['zh-hant']);
		$this->mTables['zh-my'] = array_merge ($this->mTables['zh-my'], $this->mTables['zh-hans']);
		$this->mTables['zh-sg'] = array_merge ($this->mTables['zh-sg'], $this->mTables['zh-hans']);
		$this->mTables['zh-tw'] = array_merge ($this->mTables['zh-tw'], $this->mTables['zh-hant']);
	}

	/**
	 * Reload the conversion tables.
	 *
	 * @private
	 */
	function reloadTables () {
		if ($this->mTables) {
			unset ($this->mTables);
		}

		$this->mTablesLoaded = false;
		$this->loadTables (false);
	}

	/**
	 * Translate a string to a variant.
	 *
	 * @param string $text Text to convert
	 * @param string $variant Variant language code
	 * @return string Translated text
	 */
	public function translate ($text, $variant) {
		// If $text is empty or only includes spaces, do nothing
		// Otherwise translate it
		if (trim ($text)) {
			$this->loadTables ();
			$text = strtr ($text, $this->mTables[$variant]);
		}
		return $text;
	}
}

/* End of file ZhConverter.php */
