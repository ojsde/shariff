<?php

/**
 * @defgroup plugins_generic_shariff
 */

/**
 * @file plugins/generic/shariff/index.php
 *
 * Author: Božana Bokan, Center for Digital Systems (CeDiS), Freie Universität Berlin
 * Last update: September 17, 2015
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @ingroup plugins_generic_shariff
 * @brief Wrapper for the shariff social media plugin.
 *
 */
require_once('ShariffPlugin.inc.php');

return new ShariffPlugin();

?>
