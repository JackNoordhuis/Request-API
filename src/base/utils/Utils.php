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

namespace base\utils;

class Utils {

	/**
	 * Add a .htaccess file to deny access to a directory
	 *
	 * @param $dir
	 */
	public static function denyAccessToDir($dir) {
		$file = fopen($dir . ".htaccess", "w");
		fwrite($file, "Deny from all");
		fclose($file);
	}

	/**
	 * @param $string
	 * @param $options
	 *
	 * @return mixed
	 */
	public static function jsonToArray($string, $options = JSON_OBJECT_AS_ARRAY) {
		return json_decode($string, $options);
	}

	/**
	 * @param array $data
	 * @param $options
	 *
	 * @return string
	 */
	public static function arrayToJson(array $data, $options = JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) {
		return json_encode($data, $options);
	}

	/**
	 * Get the first character in a string
	 *
	 * @param string $email
	 *
	 * @return string mixed
	 */
	public static function getFirstCharacter($email) {
		return $email{0};
	}

}