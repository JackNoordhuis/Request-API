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

interface BaseHandler {

	/**
	 * Get the name of the request
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * Get the request requires arguments or not
	 *
	 * @return bool
	 */
	public function needsArguments();

	/**
	 * Handle the request
	 *
	 * @param BaseInterface $interface
	 * @param array $data
	 *
	 * @return bool|array
	 * @throws BaseException
	 */
	public function handle(BaseInterface $interface, array $data = []);

}