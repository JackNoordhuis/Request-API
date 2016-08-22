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

// Messy garbage to load all the classes
set_include_path(get_include_path() . PATH_SEPARATOR . realpath(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "base"));

require_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "SplClassLoader.php";

$loader = new SplClassLoader("base");
$loader->register();

require "handlers/HelloWorld.php";
require "handlers/HelloWorldArgs.php";

use base\BaseInterface;
use base\BaseException;
use base\utils\Utils;
use base\api\Info;

$interface = new BaseInterface(BaseInterface::MODE_PRODUCTION, "Example-API");
if(!$interface->loadedFromCache()) {
	$interface->registerHandler(Info::class);
	$interface->registerHandler(\example\HelloWorld::class);
	$interface->registerHandler(\example\HelloWorldArgs::class);
}

try {
	// Both POST and GET requests are possible
	$data = isset($_POST["request"]) ? $_POST : $_GET;
	$result = $interface->handleRequest($data);
} catch(BaseException $e) {
	$result = [
		"status" => false,
		"message" => $e->getMessage()
	];
} finally {
	echo Utils::arrayToJson($result);
}