<?php
/**
 *
 * @package Recent Topics Extension
 * English translation by PayBas
 *
 * @copyright (c) 2015 PayBas
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Based on the original NV Recent Topics by Joas Schilling (nickvergessen)
 */

if (!defined('IN_PHPBB'))
{
        exit;
}
if (empty($lang) || !is_array($lang))
{
        $lang = array();
}

$lang = array_merge(
        $lang, array(
        'RECENT_TOPICS'     => '最新帖子',
        'RT_NO_TOPICS'          => '没有最新帖子可以展示',
        )
);