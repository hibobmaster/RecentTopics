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

class admin_controller
{
	/** @var config */
	protected $config;

	/** @var template */
	protected $template;

	/** @var language */
	protected $language;

	/** @var request */
	protected $request;

	/** @var string Custom form action */
	protected $u_action;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\extension\manager */
	protected $ext_manager;

	/**
	 * Constructor
	 *
	 * @param \phpbb\config\config				$config
	 * @param \phpbb\template\template			$template
	 * @param \phpbb\language\language			$language
	 * @param \phpbb\request\request			$request
	 * @param \phpbb\db\driver\driver_interface $db
	 * @param \phpbb\extension\manager			$ext_manager
	 *
	 */
	public function __construct
	(
		\phpbb\config\config $config,
		\phpbb\template\template $template,
		\phpbb\language\language $language,
		\phpbb\request\request $request,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\extension\manager $ext_manager
	)
	{
		$this->config		= $config;
		$this->template		= $template;
		$this->language		= $language;
		$this->request		= $request;
		$this->db			= $db;
		$this->ext_manager	= $ext_manager;
	}

	/**
	 * Display the options a user can configure for this extension
	 *
	 * @return null
	 * @access public
	 */
	public function display_options()
	{
		// Add ACP lang file
		$this->language->add_lang('info_acp_recenttopics', 'paybas/recenttopics');
		$this->language->add_lang(['viewforum', 'ucp', ]);

		add_form_key('paybas/recenttopics');

		// Is the form being submitted to us?
		if ($this->request->is_set_post('submit'))
		{
			if (!check_form_key('paybas/recenttopics'))
			{
				trigger_error($this->language->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
			}

			// Store the variable to the db
			$this->set_variable();

			// Upate user settings whith default data
			$overwrite_userset = $this->request->variable('rt_reset_default', 0);
			$this->set_user_table((bool) $overwrite_userset);

			trigger_error($this->language->lang('CONFIG_UPDATED') . adm_back_link($this->u_action));
		}

		$topic_types = [
			0 => $this->language->lang('POST') ,
			1 => $this->language->lang('POST_STICKY'),
			2 => $this->language->lang('ANNOUNCEMENTS'),
			3 => $this->language->lang('GLOBAL_ANNOUNCEMENT'),
		];

		foreach ($topic_types as $key => $topic_type)
		{
			$this->template->assign_block_vars('topiclevel_row', [
				'VALUE'		=> $key,
				'SELECTED'	=> ($this->config['rt_min_topic_level'] == $key) ? ' selected' : '',
				'OPTION'	=> $topic_type,
			]);
		}

		$display_types = [
			'RT_TOP'	 => $this->language->lang('RT_TOP'),
			'RT_BOTTOM'	 => $this->language->lang('RT_BOTTOM'),
			'RT_SIDE'	 => $this->language->lang('RT_SIDE'),
			'RT_SEPARAT' => $this->language->lang('RT_SEPARAT'),
		];

		foreach ($display_types as $key => $display_type)
		{
			$this->template->assign_block_vars('location_row', [
				'VALUE'    => $key,
				'SELECTED' => ($this->config['rt_location'] == $key) ? ' selected' : '',
				'OPTION'   => $display_type,
			]);
		}

		$this->template->assign_vars([
			'U_ACTION'				=> $this->u_action,
			'RT_INDEX'				=> (int) $this->config['rt_index'],
			'RT_PAGE_NUMBER'		=> (int) $this->config['rt_page_number'],
			'RT_PAGE_NUMBERMAX'		=> (int) $this->config['rt_page_numbermax'],
			'RT_ANTI_TOPICS'		=> $this->config['rt_anti_topics'],
			'RT_PARENTS'			=> (int) $this->config['rt_parents'],
			'RT_NUMBER'				=> (int) $this->config['rt_number'],
			'RT_SORT_START_TIME'	=> (int) $this->config['rt_sort_start_time'],
			'RT_UNREAD_ONLY'		=> (int) $this->config['rt_unread_only'],
			'RT_ON_NEWSPAGE'		=> $this->config['rt_on_newspage'],
			'S_RT_NEWSPAGE'			=> $this->ext_manager->is_enabled('nickvergessen/newspage'),
		]);

	}

	/**
	 * Store the variable to the db
	 *
	 * @return null
	 * @access protected
	 */
	protected function set_variable()
	{
		/*
		* acp options for everyone
		*/

		$this->config->set('rt_index', $this->request->variable('rt_enable', 0));

		// Maximum number of pages
		$this->config->set('rt_page_numbermax', $this->request->variable('rt_page_numbermax', 0));

		//Show all recent topic pages
		$this->config->set('rt_page_number', $this->request->variable('rt_page_number', 0));

		// Minimum topic type level
		$this->config->set('rt_min_topic_level', $this->request->variable('rt_min_topic_level', 0));

		// variable should be '' as it is a string ("1, 2, 3928") here, not an integer.
		$rt_anti_topics = $this->request->variable('rt_anti_topics', '');
		$ants = explode(",", $rt_anti_topics);
		$checkants = true;
		foreach ($ants as $ant)
		{
			if (!is_numeric($ant))
			{
				$checkants = false;
			}
		}
		if ($checkants)
		{
			$this->config->set('rt_anti_topics', $rt_anti_topics);
		}

		$this->config->set('rt_parents', $this->request->variable('rt_parents', 0));

		/*
		 *  default positions, modifiable by ucp
		 */

		$this->config->set('rt_location', $this->request->variable('rt_location', ''));

		//number of most recent topics shown per page
		$this->config->set('rt_number', $this->request->variable('rt_number', 5));

		$this->config->set('rt_sort_start_time', $this->request->variable('rt_sort_start_time', 0));

		$this->config->set('rt_unread_only', $this->request->variable('rt_unread_only', 0));

		// Enable on other extension pages?
		$this->config->set('rt_on_newspage', $this->request->variable('rt_on_newspage', 0));
	}

	/**
	 * Upate user settings whith default data
	 *
	 * @return null
	 * @access protected
	 */
	protected function set_user_table($all_user)
	{
		$sql_ary = [
			'user_rt_enable'		  => (int) $this->config['rt_index'],
			'user_rt_sort_start_time' => (int) $this->config['rt_sort_start_time'] ,
			'user_rt_unread_only'	  => (int) $this->config['rt_unread_only'],
			'user_rt_location'		  => $this->config['rt_location'],
			'user_rt_number'		  => ((int) $this->config['rt_number'] > 0 ? (int) $this->config['rt_number'] : 5 )
		];

		$sql_where = $all_user ? '' : ' WHERE user_id = ' . ANONYMOUS;

		$sql = 'UPDATE ' . USERS_TABLE . '
				SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . $sql_where;

		$this->db->sql_query($sql);
	}

	/**
	 * Set page url
	 *
	 * @param string $u_action Custom form action
	 * @return null
	 * @access public
	 */
	public function set_page_url($u_action)
	{
		$this->u_action = $u_action;
	}
}
