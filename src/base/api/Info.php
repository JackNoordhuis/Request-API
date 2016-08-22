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

namespace base\api;

use base\BaseInterface;
use base\BaseHandler;

/**
 * Basic request handler that displays the interface info
 */
class Info implements BaseHandler {

	public function getName() {
		return "Info";
	}

	public function needsArguments() {
		return false;
	}

	public function handle(BaseInterface $interface, array $data = []) {
		return [
			"status" => true,
			"name" => $interface->getName(),
			"mode" => $interface->getMode(),
			"version" => $interface::VERSION
		];
	}

}