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

namespace paybas\recenttopics\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event listener
 */
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\user */
	protected $user;

	/** @var recenttopics */
	protected $rt_functions;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var template */
	protected $template;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var auth */
	protected $auth;

	/**
	 * listener constructor.
	 *
	 * @param \phpbb\user							 $user
	 * @param \paybas\recenttopics\core\recenttopics $functions
	 * @param \phpbb\config\config					 $config
	 * @param \phpbb\request\request				 $request
	 * @param \phpbb\template\template				 $template
	 * @param \phpbb\controller\helper				 $helper
	 * @param \phpbb\language\language 				 $language
	 * @param \phpbb\auth\auth						 $auth
	 */
	public function __construct
	(
		\phpbb\user $user,
		\paybas\recenttopics\core\recenttopics $functions,
		\phpbb\config\config $config,
		\phpbb\request\request $request,
		\phpbb\template\template $template,
		\phpbb\controller\helper $helper,
		\phpbb\language\language $language,
		\phpbb\auth\auth $auth
	)
	{
		$this->user			= $user;
		$this->rt_functions	= $functions;
		$this->config		= $config;
		$this->request		= $request;
		$this->template		= $template;
		$this->helper		= $helper;
		$this->language		= $language;
		$this->auth			= $auth;
	}

	/**
	 * Get subscribed events
	 *
	 * @return array
	 * @static
	 */
	public static function getSubscribedEvents()
	{
		return [
			'core.page_header'						 => 'set_template_vars',
			'core.index_modify_page_title'           => 'display_rt',
			'nickvergessen.newspage.newspage'        => 'display_rt_newspage',
			'core.acp_manage_forums_request_data'    => 'acp_manage_forums_request_data',
			'core.acp_manage_forums_initialise_data' => 'acp_manage_forums_initialise_data',
			'core.acp_manage_forums_display_form'    => 'acp_manage_forums_display_form',
			'core.permissions'                       => 'add_permission',

			// Events added by this extension
			'paybas.recenttopics.topictitle_remove_re'  => 'topictitle_remove_re',
		];
	}

	/**
	 * Set template vars and load language
	 *
	 * @return null
	 * @access public
	 */
	public function set_template_vars()
	{
		$this->template->assign_vars([
			'U_RT_PAGE_SEPARATE'  => $this->helper->route('paybas_recenttopics_page_controller', ['page' => 'separate']),
			'S_RT_LINK_IN_NAVBAR' => $this->auth->acl_get('u_rt_view') && $this->config['rt_index'] && $this->user->data['user_rt_enable'] && $this->user->data['user_rt_location'] == 'RT_SEPARAT',
		]);

		$this->language->add_lang('recenttopics', 'paybas/recenttopics');
	}

	/**
	 * The main magic
	 *
	 * @return null
	 * @access public
	 */
	public function display_rt()
	{
		if (isset($this->config['rt_index']) && $this->config['rt_index'])
		{
			$this->rt_functions->display_recent_topics();
		}
	}

	/**
	 * nickvergessen's newspage ext
	 *
	 * @return null
	 * @access public
	 */
	public function display_rt_newspage()
	{
		if (isset($this->config['rt_on_newspage']) && $this->config['rt_on_newspage'])
		{
			$this->rt_functions->display_recent_topics();
		}
	}

	/**
	 * Submit form (add/update)
	 *
	 * @param \phpbb\event\data $event The event object
	 * @return null
	 * @access public
	 */
	public function acp_manage_forums_request_data($event)
	{
		$array = $event['forum_data'];
		$array['forum_recent_topics'] = $this->request->variable('forum_recent_topics', 1);
		$event['forum_data'] = $array;
	}

	/**
	 * Default settings for new forums
	 *
	 * @param \phpbb\event\data $event The event object
	 * @return null
	 * @access public
	 */
	public function acp_manage_forums_initialise_data($event)
	{
		if ($event['action'] == 'add')
		{
			$array = $event['forum_data'];
			$array['forum_recent_topics'] = '1';
			$event['forum_data'] = $array;
		}
	}

	/**
	 * ACP forums template output
	 *
	 * @param \phpbb\event\data $event The event object
	 * @return null
	 * @access public
	 */
	public function acp_manage_forums_display_form($event)
	{
		$array = $event['template_data'];
		$array['RECENT_TOPICS'] = $event['forum_data']['forum_recent_topics'];
		$event['template_data'] = $array;
	}

	/**
	 * Add permissions
	 *
	 * @param \phpbb\event\data $event The event object
	 * @return null
	 * @access public
	 */
	public function add_permission($event)
	{
		$permissions = $event['permissions'];
		$categories = $event['categories'];
		$categories['recenttopics'] = 'ACL_CAT_RTNG';
		$permissions['u_rt_view']				= ['lang' => 'ACL_U_RTNG_VIEW'				, 'cat' => 'recenttopics'];
		$permissions['u_rt_enable']				= ['lang' => 'ACL_U_RTNG_ENABLE'			, 'cat' => 'recenttopics'];
		$permissions['u_rt_location']			= ['lang' => 'ACL_U_RTNG_LOCATION'			, 'cat' => 'recenttopics'];
		$permissions['u_rt_sort_start_time']	= ['lang' => 'ACL_U_RTNG_SORT_START_TIME'	, 'cat' => 'recenttopics'];
		$permissions['u_rt_unread_only']		= ['lang' => 'ACL_U_RTNG_UNREAD_ONLY'		, 'cat' => 'recenttopics'];
		$permissions['u_rt_number']				= ['lang' => 'ACL_U_RTNG_NUMBER'			, 'cat' => 'recenttopics'];
		$event['permissions'] = $permissions;
		$event['categories'] = $categories;
	}

	/**
	 * @event paybas.recenttopics.topictitle_remove_re
	 * remove "Re: " from post subject
	 *
	 * @param \phpbb\event\data		$event  The event object
	 * @return null
	 * @access public
	 */
	public function topictitle_remove_re($event)
	{
		if (isset($event['row']['topic_last_post_subject']))
		{
			$array = (array) $event['row'];
			$lastpost = $array['topic_last_post_subject'];
			$array['topic_last_post_subject'] = preg_replace('/^Re: /', '', $lastpost);
			$event['row'] = $array;
		}
	}
}
