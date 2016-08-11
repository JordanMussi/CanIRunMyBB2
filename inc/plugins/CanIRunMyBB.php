<?php
/**
 *
 * @package    Can I Run MyBB 2.0?
 * @author     Jordan Mussi <https://jordanmussi.github.io>
 * @version    1.1
 * @copyright  Copyright (c) 2016, Jordan Mussi All Rights Reserved.
 * @license    https://github.com/JordanMussi/CanIRunMyBB2/blob/master/LICENSE.txt BSD-3
 *
 */

if(!defined('IN_MYBB'))
{
	die('Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.<br /><br /><a href="http://docs.mybb.com/Free_never_tasted_so_good.html" title="Free never tasted so good" target="_blank">Free never tasted so good!</a>');
}

define('MYBB2_PHP_VERSION', '7.0.0');

function CanIRunMyBB_info()
{
	return array(
		'name'			=> 'Can I Run MyBB 2.0?',
		'description'	=> 'A simple plugin to check if your web host can support MyBB 2.0. A list of known compatible web hosts can be found in the <a href="https://github.com/mybb/2.0-Hosts" title="MyBB 2.0 Compatible Web Hosts" target="_blank">mybb/2.0-Hosts</a> GitHub repository.<br />Please note that this test does not fully guarantee that a host is going to work flawlessly with MyBB 2.0.<br />'.CanIRunMyBB_delete_link(),
		'website'		=> 'https://jordanmussi.github.io/CanIRunMyBB2/',
		'author'		=> 'Jordan Mussi',
		'authorsite'	=> 'https://jordanmussi.github.io',
		'guid'			=> '',
		'version'		=> '1.1',
		'compatibility' => '*'
	);
}

function CanIRunMyBB_activate()
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
			CanIRunMyBB_delete();
		}
	}

	$report = array(
		'error' => array(),
		'success' => array()
	);

	if(version_compare(PHP_VERSION, MYBB2_PHP_VERSION, '<')) {
		$report['error'][] = 'You are currently running PHP '.PHP_VERSION." (MyBB 2.0 requires ".MYBB2_PHP_VERSION.' or above).';
	} else {
		$report['success'][] = 'You are currently running PHP '.PHP_VERSION." (MyBB 2.0 requires ".MYBB2_PHP_VERSION.' or above).';
	}

	if(!extension_loaded('openssl')) {
		$report['error'][] = 'You do not have the OpenSSL PHP extension loaded.';
	} else {
		$report['success'][] = 'The OpenSSL PHP extension is loaded.';
	}

	if(!extension_loaded('pdo')) {
		$report['error'][] = 'You do not have the PDO PHP extension loaded.';
	} else {
		$report['success'][] = 'The PDO PHP extension is loaded.';
	}

	if(!extension_loaded('mbstring')) {
		$report['error'][] = 'You do not have the Mbstring PHP extension loaded.';
	} else {
		$report['success'][] = 'The Mbstring PHP extension is loaded.';
	}

	if(!extension_loaded('tokenizer')) {
		$report['error'][] = 'You do not have the Tokenizer PHP extension loaded.';
	} else {
		$report['success'][] = 'The Tokenizer PHP extension is loaded.';
	}

	if(count($report['error']) > 0) {
		// :(
		flash_message('It looks like your web host might not support MyBB 2.0. Please contact your web host\'s support team for assistance.'.CanIRunMyBB_report_list($report).'A list of known MyBB 2.0 compatible web hosts can be found in the <a href="https://github.com/mybb/2.0-Hosts" title="MyBB 2.0 Compatible Web Hosts" target="_blank">mybb/2.0-Hosts</a> GitHub repository.<br /><span style="font-size: 10px;">Please note that this test does not fully guarantee that a host is going to work flawlessly with MyBB 2.0. '.CanIRunMyBB_delete_link().'</span>', 'error');
		admin_redirect('index.php?module=config-plugins');
	}
	else
	{
		// :)
		flash_message('It looks like your web host supports MyBB 2.0.'.CanIRunMyBB_report_list($report).'A list of known MyBB 2.0 compatible web hosts can be found in the <a href="https://github.com/mybb/2.0-Hosts" title="MyBB 2.0 Compatible Web Hosts" target="_blank">mybb/2.0-Hosts</a> GitHub repository. If yours isn\'t listed you can send a Pull Request!<br /><span style="font-size: 10px;">Please note that this test does not fully guarantee that a host is going to work flawlessly with MyBB 2.0. '.CanIRunMyBB_delete_link().'</span>', 'success');
		admin_redirect('index.php?module=config-plugins');
	}
}

function CanIRunMyBB_deactivate()
{

}

function CanIRunMyBB_delete_link()
{
	global $mybb;

	return 'Once you are done with this tool, you can <a href="index.php?module=config-plugins&action=activate&do=delete&plugin=CanIRunMyBB&my_post_key='.$mybb->post_code.'">delete it automatically</a>.';
}

function CanIRunMyBB_report_list($report)
{
	$list_items = '';
	foreach($report['erros'] as $error) {
		$list_items .= "<li><span style=\"background: url('./styles/default/images/icons/error.png') no-repeat 0px 8px; padding: 10px 10px 10px 10px;\"></span>{$error}</li>";
	}
	foreach($report['success'] as $success) {
		$list_items .= "<li style=\"color: #080;\"><span style=\"background: url('./styles/default/images/icons/success.png') no-repeat 0px 8px; padding: 10px 10px 10px 10px;\"></span>{$success}</li>";
	}
	return "<ul style=\"line-height: 1.5\">{$list_items}</ul>";
}

function CanIRunMyBB_delete()
{
	if(!unlink(MYBB_ROOT.'/inc/plugins/CanIRunMyBB.php'))
	{
		flash_message('There was an error. You will have to delete this plugin manually.', 'error');
		admin_redirect('index.php?module=config-plugins');
	}
	else
	{
		flash_message('The Can I run MyBB 2.0? plugin was successfully deleted. Have a nice day! <a href="http://docs.mybb.com/Free_never_tasted_so_good.html" title="Free never tasted so good" target="_blank">Free never tasted so good!</a>', 'success');
		admin_redirect('index.php?module=config-plugins');
	}
}
