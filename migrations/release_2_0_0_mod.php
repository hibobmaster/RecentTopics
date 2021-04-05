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

class release_2_0_0_mod extends \phpbb\db\migration\migration
{

    public static function depends_on()
    {
        return ['\paybas\recenttopics\migrations\release_2_0_0'];
    }

	public function update_data()
	{
	
	
			/*
			// Remove old (v1) modules
			array('if', array(
				array('module.exists', array('acp', 'RECENT_TOPICS_MOD', array(
					'module_basename'    => 'recenttopics',
					'modes'    => array('overview'),
				),
				)),
				array('module.remove', array('acp', 'RECENT_TOPICS_MOD', array(
					'module_basename'    => 'recenttopics',
					'modes'    => array('overview'),
				),
				)),
			)),
			array('if', array(
				array('module.exists', array('acp', 'ACP_CAT_DOT_MODS', 'RECENT_TOPICS_MOD')),
				array('module.remove', array('acp', 'ACP_CAT_DOT_MODS', 'RECENT_TOPICS_MOD')),
			)),

			// Remove early beta modules
			array('if', array(
				array('module.exists', array('acp', 'ACP_CAT_DOT_MODS', array(
					'module_basename'    => '\paybas\recenttopics\acp\recenttopics_module',
					'modes'    => array('recenttopics_config'),
				),
				)),
				array('module.remove', array('acp', 'ACP_CAT_DOT_MODS', array(
					'module_basename'    => '\paybas\recenttopics\acp\recenttopics_module',
					'modes'    => array('recenttopics_config'),
				),
				)),
			)),

			array('if', array(
				array('module.exists', array('acp', 'ACP_CAT_DOT_MODS', 'RECENT_TOPICS_EXT')),
				array('module.remove', array('acp', 'ACP_CAT_DOT_MODS', 'RECENT_TOPICS_EXT')),
			)),
			*/
			
			
		return [
			// Add new modules
			['module.add', [
				'acp',
				'ACP_CAT_DOT_MODS',
				'RECENT_TOPICS'
            ]],

			['module.add', [
				'acp',
				'RECENT_TOPICS',
				[
					'module_basename'    => '\paybas\recenttopics\acp\recenttopics_module',
					'modes'    => ['recenttopics_config'],
                ],
            ]],
        ];
	}

	public function revert_data()
	{
		return array(
			array('module.remove', array(
				'acp',
				'RECENT_TOPICS',
				array(
                    'module_basename'    => '\paybas\recenttopics\acp\recenttopics_module',
					'modes'    => array('recenttopics_config'),
				),
			)),
			array('module.remove', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'RECENT_TOPICS'
			)),
		);
	}
}
