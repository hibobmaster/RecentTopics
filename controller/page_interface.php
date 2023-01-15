<?php
/**
 *
 * Recent Topics. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2022, IMC, https://github.com/IMC-GER / LukeWCS, https://github.com/LukeWCS
 * @copyright (c) 2017, Sajaki, https://www.avathar.be
 * @copyright (c) 2015, PayBas
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 *
 * Based on the original NV Recent Topics by Joas Schilling (nickvergessen)
 */

namespace paybas\recenttopics\controller;

interface page_interface
{
	/**
	 * Display the page
	 *
	 * @param string $route The route name for a page
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 * @access public
	 */
	public function display();
}
