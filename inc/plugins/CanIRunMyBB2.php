<?php
/**
 *
 * @package    Can I Run MyBB 2.0?
 * @author     Jordan Mussi <https://jordanmussi.github.io>
 * @version    1.0
 * @copyright  Copyright (c) 2016, Jordan Mussi All Rights Reserved.
 * @license    https://github.com/JordanMussi/CanIRunMyBB2/LICENSE.txt BSD-3
 *
 */

if(!defined('IN_MYBB'))
{
	die('Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.<br /><br /><a href="http://docs.mybb.com/Free_never_tasted_so_good.html" title="Free never tasted so good" target="_blank">Free never tasted so good!</a>');
}

define('MYBB2_PHP_VERSION', '5.5.9');

function CanIRunMyBB2_info()
{
	return array(
		'name'			=> 'Can I Run MyBB 2.0?',
		'description'	=> 'A simple plugin to check if your web host can support MyBB 2.0. Please note that this test does not fully guarantee that a host is going to work flawlessly with MyBB 2.0.<br />A list of known MyBB 2.0 compatible web hosts can be found in the <a href="https://github.com/mybb/2.0-Hosts" title="MyBB 2.0 Compatible Web Hosts" target="_blank">mybb/2.0-Hosts</a> GitHub repository.',
		'website'		=> 'https://jordanmussi.github.io/CanIRunMyBB2/',
		'author'		=> 'Jordan Mussi',
		'authorsite'	=> 'https://jordanmussi.github.io',
		'guid'			=> '',
		'version'		=> '1.0',
		'compatibility' => '*'
	);
}

function CanIRunMyBB2_activate()
{
	global $mybb;

	if($mybb->get_input('do') == 'delete')
	{
		if(!verify_post_check($mybb->input['my_post_key']))
		{
			global $lang;
			flash_message($lang->invalid_post_verify_key2, 'error');
			admin_redirect('index.php?module=config-plugins');
		}
		else
		{
			CanIRunMyBB2_delete();
		}
	}

	if(version_compare(PHP_VERSION, MYBB2_PHP_VERSION, '<'))
	{
		// :(
		flash_message('It looks like your web host might not support MyBB 2.0. You are currently running PHP '.PHP_VERSION.", MyBB 2.0 requires ".MYBB2_PHP_VERSION.' or above. Please check with your host\'s support team to see if you are able to use a later PHP version.<br />A list of known MyBB 2.0 compatible web hosts can be found in the <a href="https://github.com/mybb/2.0-Hosts" title="MyBB 2.0 Compatible Web Hosts" target="_blank">mybb/2.0-Hosts</a> GitHub repository.<br /><span style="font-size: 10px;">Please note that this test does not fully guarantee that a host is going to work flawlessly with MyBB 2.0. Once you are done with this tool, you can <a href="index.php?module=config-plugins&action=activate&do=delete&plugin=CanIRunMyBB2&my_post_key='.$mybb->post_code.'">delete it automatically</a>.</span>', 'error');
		admin_redirect('index.php?module=config-plugins');
	}
	else
	{
		// :)
		flash_message('It looks like your web host supports MyBB 2.0. You are currently running PHP '.PHP_VERSION.", MyBB 2.0 requires ".MYBB2_PHP_VERSION.' or above.<br />A list of known MyBB 2.0 compatible web hosts can be found in the <a href="https://github.com/mybb/2.0-Hosts" title="MyBB 2.0 Compatible Web Hosts" target="_blank">mybb/2.0-Hosts</a> GitHub repository. If yours isn\'t listed please send a Pull Request!<br /><span style="font-size: 10px;">Please note that this test does not fully guarantee that a host is going to work flawlessly with MyBB 2.0. Once you are done with this tool, you can <a href="index.php?module=config-plugins&action=activate&do=delete&plugin=CanIRunMyBB2&my_post_key='.$mybb->post_code.'">delete it automatically</a>.</span>', 'success');
		admin_redirect('index.php?module=config-plugins');
	}
}

function CanIRunMyBB2_deactivate()
{

}

function CanIRunMyBB2_delete()
{
	if(!unlink(MYBB_ROOT.'/inc/plugins/CanIRunMyBB2.php'))
	{
		flash_message('There was an error. You will have to delete the Can I run MyBB 2.0? plugin manually.', 'error');
		admin_redirect('index.php?module=config-plugins');
	}
	else
	{
		flash_message('The Can I run MyBB 2.0? plugin was deleted automatically. Have a nice day! <a href="http://docs.mybb.com/Free_never_tasted_so_good.html" title="Free never tasted so good" target="_blank">Free never tasted so good!</a>', 'success');
		admin_redirect('index.php?module=config-plugins');
	}
}
