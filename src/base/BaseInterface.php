<?php

/*
 * REST Base
 *
 * Copyright (C) 2016 Jack Noordhuis
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author Jack Noordhuis
 */

namespace base;

use base\utils\Utils;

/**
 * Base interface to handle REST based requests
 */
class BaseInterface {

	/** @var string */
	protected $mode = "";

	/** @var string */
	protected $name = "";

	/** @var string */
	private $rootDir = "";

	/** @var bool */
	private $loadedCache = false;

	/** @var BaseHandler[] */
	protected $handlerMap = [];

	/** Modes */
	const MODE_DEBUG = "debug";
	const MODE_PRODUCTION = "production";

	/** Constants */
	const VERSION = "v0.0.1-dev";

	/**
	 * BaseInterface constructor
	 *
	 * @param string $mode
	 * @param string $name
	 * @param bool $unloadCache
	 */
	public function __construct($mode = self::MODE_DEBUG, $name = "Base-API", $unloadCache = true) {
		$this->mode = $mode;
		$this->name = $name;
		$this->rootDir = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR;

		if($unloadCache and $this->cacheExists() and $this->mode !== self::MODE_DEBUG) {
			$this->handlerMap = $this->readCacheData(file_get_contents($this->getCachePath()));
			$this->loadedCache = true;
		}
	}

	/**
	 * Cache the server interface
	 */
	public function __destruct() {
		if(!$this->loadedCache and $this->mode !== self::MODE_DEBUG) {
			$this->cache();
		}
	}

	/**
	 * @param array $data
	 *
	 * @return bool
	 * @throws BaseException
	 */
	public function handleRequest(array $data) {
			if(isset($data["request"])) {
				$request = preg_replace("/[^a-z0-9 ]/", "", strtolower($data["request"]));
				if(isset($this->handlerMap[$request])) {
					$handler = $this->getHandler($request);
					if(!$handler->needsArguments()) {
						return $handler->handle($this);
					} else {
						if(isset($data["args"])) {
							return $handler->handle($this, Utils::jsonToArray($data["args"]));
						} else {
							throw new BaseException("Invalid request, no arguments specified!");
						}
					}
				} else {
					throw new BaseException("Invalid request, request does not exist!");
				}
			} else {
				throw new BaseException("Invalid request, please specify the request type!");
			}
	}

	/**
	 * Add a handler to the map
	 *
	 * @param string $className
	 *
	 * @return bool
	 */
	public function registerHandler($className) {
		$class = new \ReflectionClass($className);
		if($class->implementsInterface(BaseHandler::class) and !$class->isAbstract()) {
			$this->handlerMap[strtolower($class->getShortName())] = $className;
			return true;
		}
		return false;
	}

	/**
	 * @param $name
	 *
	 * @return BaseHandler
	 */
	public function getHandler($name) {
		$class = $this->handlerMap[$name];
		return new $class;
	}

	/**
	 * Get the interfaces name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getMode() {
		return $this->mode;
	}

	/**
	 * Get the root directory
	 *
	 * @return string
	 */
	public function getRootDir() {
		return $this->rootDir;
	}

	/**
	 * Get the cache save ID
	 *
	 * @return string
	 */
	public function getSaveId() {
		return "{$this->name}_cache_{$this->mode}_" . self::VERSION;
	}

	/**
	 * Get the directory cache data is stored in
	 *
	 * @return string
	 */
	public function getCacheDir() {
		return $this->rootDir . ".cache" . DIRECTORY_SEPARATOR;
	}

	/**
	 * @return bool
	 */
	protected function cacheExists() {
		return file_exists($this->getCachePath());
	}

	/**
	 * Get the path to the interface's cache
	 *
	 * @return string
	 */
	public function getCachePath() {
		return $this->getCacheDir() . $this->getSaveId() . ".json";
	}

	/**
	 * Check if the interface was loaded from the cache
	 *
	 * @return bool
	 */
	public function loadedFromCache() {
		return $this->loadedCache;
	}

	/**
	 * Save a snapshot of the handlers map for easy reloading
	 */
	protected function cache() {
		if(!is_dir($this->getCacheDir())) {
			mkdir($this->getCacheDir());
			Utils::denyAccessToDir($this->getCacheDir());
		}
		$file = fopen($this->getCachePath(), "w");
		fwrite($file, $this->parseCacheData());
		fclose($file);
	}

	/**
	 * Get a parsed version of the cache data
	 *
	 * @return string
	 */
	private function parseCacheData() {
		$data = [];
		foreach($this->handlerMap as $key => $handler) {
			$data[$key] = serialize($handler);
		}
		return Utils::arrayToJson($data);
	}

	/**
	 * Read the parsed cache data
	 *
	 * @param string $string
	 *
	 * @return array
	 */
	private function readCacheData($string) {
		$data = [];
		foreach(Utils::jsonToArray($string) as $key => $handler) {
			$data[$key] = unserialize($handler);
		}
		return $data;
	}

}