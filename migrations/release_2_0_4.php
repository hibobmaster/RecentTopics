<?php
/**
 *
 * @package Recent Topics Extension
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Based on the original NV Recent Topics by Joas Schilling (nickvergessen)
 */

namespace paybas\recenttopics\migrations;

class release_2_0_4 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['rt_version']) && version_compare($this->config['rt_version'], '2.0.4', '>=');
	}

	public static function depends_on()
	{
		return [
			'\paybas\recenttopics\migrations\release_2_0_0',
		];
	}

	public function update_data()
	{
		return [
			['config.update', ['rt_version', '2.0.4']],
			['config.add', ['rt_min_topic_level', 0]],
		];
	}
}
