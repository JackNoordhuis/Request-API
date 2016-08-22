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

class TokenGenerator {

	/** @var string */
	protected $alphabet;

	/** @var int */
	protected $alphabetLength;


	/**
	 * @param string $alphabet
	 */
	public function __construct($alphabet = "") {
		if($alphabet != "") {
			$this->setAlphabet($alphabet);
		} else {
			$this->setAlphabet(implode(range("a", "z")) . implode(range("A", "Z")) . implode(range(0, 9)));
		}
	}

	/**
	 * @param string $alphabet
	 */
	public function setAlphabet($alphabet) {
		$this->alphabet = $alphabet;
		$this->alphabetLength = strlen($alphabet);
	}

	/**
	 * @param int $length
	 *
	 * @return string
	 */
	public function generate($length) {
		$token = "";

		for($i = 0; $i < $length; $i++) {
			$randomKey = TokenGenerator::getRandomInteger(0, $this->alphabetLength);
			$token .= $this->alphabet[$randomKey];
		}

		return $token;
	}

	/**
	 * @param int $length
	 * @param string $alphabet
	 *
	 * @return string
	 */
	public static function quickGenerate($length, $alphabet = "") {
		if($alphabet !== "") {
			$alphabet = implode(range("A", "Z")) . implode(range(0, 9));
		}
		$alphabetLength = strlen($alphabet);
		$token = "";

		for($i = 0; $i < $length; $i++) {
			$randomKey = TokenGenerator::getRandomInteger(0, $alphabetLength);
			$token .= $alphabet[$randomKey];
		}

		return $token;
	}

	/**
	 * @param int $min
	 * @param int $max
	 *
	 * @return int
	 */
	protected static function getRandomInteger($min, $max) {
		$range = ($max - $min);

		if($range < 0) {
			return $min;
		}

		$log = log($range, 2);
		$bytes = (int)($log / 8) + 1;
		$bits = (int)$log + 1;
		$filter = (int)(1 << $bits) - 1;

		do {
			$rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
			$rnd = $rnd & $filter;

		} while($rnd >= $range);

		return ($min + $rnd);
	}

}