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

namespace example;

use base\BaseHandler;
use base\BaseInterface;

/**
 * Basic example of a request that requires no arguments
 */
class HelloWorld implements BaseHandler {

	public function getName() {
		return "HelloWorld";
	}

	public function needsArguments() {
		return false;
	}

	public function handle(BaseInterface $interface, array $data = []) {
		return [
			"status" => true,
			"result" => "Hello World!"
		];
	}

}