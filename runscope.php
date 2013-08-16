<?php

/*
Plugin Name: Runscope
Plugin URI: http://github.com/runscope/wordpress
Description: Allows developers to turn on complete or partial interception of any request through the WordPress HTTP API using Runscope.
Version: 0.1
Author: Levers
Author URI: http://leve.rs
License: GPL2

Copyright 2013  Levers LLC  (email : info@leve.rs)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

include('runscopeSettings.php');

function runRequest()
{
	add_filter('pre_http_request', 'runscopeRequest',10, 3);
}

add_action('init', 'runRequest');

function runscopeRequest($content, $r, $url)
{
	$bucket = get_option( 'runscope_bucket_id' );

	if(empty($bucket))
	{
		return false;
	}

	if(strstr($url, 'runscope'))
	{
		return false;
	}

	$runscopekey = $bucket;

	$parsed = parse_url($url);

	$parsed['host'] = str_replace('.', '-', $parsed['host']).'-'.$runscopekey.'.runscope.net';

	$url = unparse_url($parsed);

	if(isset($r['headers']['Host']))
	{
		$r['headers']['Host'] = $parsed['host'];
	}

	$http = _wp_http_get_object();

	$http->request($url, $r);

	return false;
}

function unparse_url($parsed_url) {
	$scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
	$host     = isset($parsed_url['host']) ? $parsed_url['host'] : '';
	$port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
	$user     = isset($parsed_url['user']) ? $parsed_url['user'] : '';
	$pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : '';
	$pass     = ($user || $pass) ? "$pass@" : '';
	$path     = isset($parsed_url['path']) ? $parsed_url['path'] : '';
	$query    = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
	$fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';
	return "$scheme$user$pass$host$port$path$query$fragment";
}
