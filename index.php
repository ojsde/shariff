<?php

/**
 * @defgroup plugins_generic_shariff Shariff Plugin
 */

/**
 * @file plugins/generic/shariff/index.php
 *
 * Copyright (c) 2018 Free University Berlin
 * Distributed under the GNU GPL v2. For full terms see the file LICENSE.
 *
 * @ingroup plugins_generic_shariff
 * @brief Wrapper for Shariff plugin.
 *
 */
require_once('ShariffPlugin.inc.php');

return new ShariffPlugin();

?>
