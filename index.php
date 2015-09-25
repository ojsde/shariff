<?php

/**
 * @defgroup plugins_generic_shariff
 */

/**
 * @file index.php
 *
 * Author: Božana Bokan, Center for Digital Systems (CeDiS), Freie Universität Berlin
 * Last update: September 24, 2015
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @package plugins.generic.shariff

 * @brief Wrapper for the shariff social media plugin.
 *
 */
require_once('ShariffPlugin.inc.php');

return new ShariffPlugin();

?>
