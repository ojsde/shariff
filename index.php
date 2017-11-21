<?php

/**
 * @defgroup plugins_generic_shariff Shariff Plugin
 */

/**
 * @file plugins/generic/shariff/index.php
 *
 * Copyright (c) 2014-2017 Simon Fraser University
 * Copyright (c) 2003-2017 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @ingroup plugins_generic_shariff
 * @brief Wrapper for Shariff plugin.
 *
 */
require_once('ShariffPlugin.inc.php');

return new ShariffPlugin();

?>
