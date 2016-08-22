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

/**
 * SplClassLoader implementation that implements the technical interoperability
 * standards for PHP 5.3 namespaces and class names
 *
 * Improved by Jack Noordhuis (https://twitter.com/JackNoordhuis)
 *
 * Original gist: https://gist.github.com/jwage/221634
 */
class SplClassLoader {

	/** @var string */
	private $fileExtension = ".php";

	/** @var string */
	private $namespace = "";

	/** @var string */
	private $includePath = "";

	/** @var string */
	private $namespaceSeparator = "\\";

	/**
	 * Creates a new SplClassLoader that loads classes of the specified namespace.
	 *
	 * @param string $ns The namespace to use.
	 * @param string $includePath
	 */
	public function __construct($ns = null, $includePath = null) {
		$this->namespace = $ns;
		$this->includePath = $includePath;
	}

	/**
	 * Sets the namespace separator used by classes in the namespace of this class loader.
	 *
	 * @param string $sep The separator to use.
	 */
	public function setNamespaceSeparator($sep) {
		$this->namespaceSeparator = $sep;
	}

	/**
	 * Gets the namespace separator used by classes in the namespace of this class loader.
	 *
	 * @return string
	 */
	public function getNamespaceSeparator() {
		return $this->namespaceSeparator;
	}

	/**
	 * Sets the base include path for all class files in the namespace of this class loader.
	 *
	 * @param string $includePath
	 */
	public function setIncludePath($includePath) {
		$this->includePath = $includePath;
	}

	/**
	 * Gets the base include path for all class files in the namespace of this class loader.
	 *
	 * @return string $includePath
	 */
	public function getIncludePath() {
		return $this->includePath;
	}

	/**
	 * Sets the file extension of class files in the namespace of this class loader.
	 *
	 * @param string $fileExtension
	 */
	public function setFileExtension($fileExtension) {
		$this->fileExtension = $fileExtension;
	}

	/**
	 * Gets the file extension of class files in the namespace of this class loader.
	 *
	 * @return string $fileExtension
	 */
	public function getFileExtension() {
		return $this->fileExtension;
	}

	/**
	 * Installs this class loader on the SPL autoload stack.
	 */
	public function register() {
		spl_autoload_register(array($this, "loadClass"));
	}

	/**
	 * Uninstalls this class loader from the SPL auto-loader stack.
	 */
	public function unregister() {
		spl_autoload_unregister(array($this, "loadClass"));
	}

	/**
	 * Loads the given class or interface.
	 *
	 * @param string $className The name of the class to load.
	 *
	 * @return void
	 */
	public function loadClass($className) {
		if($this->namespace == null || $this->namespace . $this->namespaceSeparator === substr($className, 0, strlen($this->namespace . $this->namespaceSeparator))) {
			$fileName = "";
			$namespace = "";
			if(($lastNsPos = strripos($className, $this->namespaceSeparator)) !== false) {
				$namespace = substr($className, 0, $lastNsPos);
				$className = substr($className, $lastNsPos + 1);
				$fileName = str_replace($this->namespaceSeparator, DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
			}
			$fileName .= str_replace("_", DIRECTORY_SEPARATOR, $className) . $this->fileExtension;
			require ($this->includePath !== null ? $this->includePath . DIRECTORY_SEPARATOR : "") . $fileName;
		}
	}
}