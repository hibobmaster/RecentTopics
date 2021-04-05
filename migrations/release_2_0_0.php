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

class release_2_0_0 extends \phpbb\db\migration\migration
{

	public function effectively_installed()
	{
		return isset($this->config['rt_version']) && version_compare($this->config['rt_version'], '2.0.0', '>=');
	}

	static public function depends_on()
	{
        return ['\phpbb\db\migration\data\v320\v320'];
	}


	public function update_schema()
	{
		return [
			'add_columns' => [
				$this->table_prefix . 'forums' => [
					'forum_recent_topics' => ['TINT:1', 1],
                ],
            ],
        ];
	}

	public function revert_schema()
	{
		return [
			'drop_columns' => [
				$this->table_prefix . 'forums' => [
					'forum_recent_topics',
                ],
            ],
        ];
	}

	public function update_data()
	{
		return [
			// Add new config vars
			['config.add', ['rt_version', '2.0.0']],
			['config.add', ['rt_number', 5]],
			['config.add', ['rt_page_number', 0]],
			['config.add', ['rt_anti_topics', 0]],
			['config.add', ['rt_parents', 1]],
			['config.add', ['rt_unreadonly', 0]],
			['config.add', ['rt_index', 1]],
        ];
	}

	public function revert_data()
	{
		return [
			['config.remove', ['rt_version']],
			['config.remove', ['rt_number']],
			['config.remove', ['rt_page_number']],
			['config.remove', ['rt_anti_topics']],
			['config.remove', ['rt_parents']],
			['config.remove', ['rt_unreadonly']],
			['config.remove', ['rt_index']],
        ];
	}
}
