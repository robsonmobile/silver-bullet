<?php
// admin "Silver Bullet" functions

function silverbullet_insert_with_markers( $filename, $insertion ) {
	$marker = 'Silver Bullet';

	if (!file_exists( $filename ) || is_writeable( $filename ) ) {
		if (!file_exists( $filename ) ) {
			$markerdata = '';
		} else {
			$markerdata = explode( "\n", implode( '', file( $filename ) ) );
		}

		if ( !$f = @fopen( $filename, 'w' ) ) {
			return false;
		}

		$foundit = false;
		if ( $markerdata ) {
			$state = true;
			foreach ( $markerdata as $n => $markerline ) {
				if (strpos($markerline, '# BEGIN ' . $marker) !== false) {
					$state = false;
				}
				if ( $state ) {
					if ( $n + 1 < count( $markerdata ) ) {
						fwrite($f, "{$markerline}\n");
					} else {
						fwrite($f, "{$markerline}");
					}
				}
				if (strpos($markerline, '# END ' . $marker) !== false) {
					fwrite( $f, "# BEGIN {$marker}\n" );
					if ( is_array( $insertion )) {
						foreach ($insertion as $insertline) {
							fwrite($f, "{$insertline}\n");
						}
					}
					fwrite( $f, "# END {$marker}\n" );
					$state = true;
					$foundit = true;
				}
			}
		}
		if (!$foundit) {
			/*fwrite( $f, "\n# BEGIN {$marker}\n" ); // writes to the end of the file
			foreach ( $insertion as $insertline ) {
				fwrite($f, "{$insertline}\n");
			}
			fwrite( $f, "# END {$marker}\n" );*/

			// writes to the beginning of the file
			$silverbullet_rules = "\n# BEGIN {$marker}\n";
			foreach ( $insertion as $insertline ) {
				$silverbullet_rules .= "{$insertline}\n";
			}
			$silverbullet_rules .= "# END {$marker}\n";
			$file_content = file_get_contents( $filename );
			file_put_contents( $filename, $silverbullet_rules . "\n" . $file_content );
		}
		fclose( $f );
		return true;
	} else {
		return false;
	}
}


function silverbullet_update_htaccess( $options ) {
	global $wp_rewrite;

	$home_path = get_home_path();
	$htaccess_file = $home_path.'.htaccess';

	//$existing_rules  = array_filter( extract_from_markers( $htaccess_file, 'SBP' ) );
	//$new_rules = array_filter( explode( "\n", $wp_rewrite->mod_rewrite_rules() ) );

	$home_root = parse_url(home_url());
	if ( isset( $home_root['path'] ) ) {
		$home_root = trailingslashit($home_root['path']);
	} else {
		$home_root = '/';
	}

	$redirect_url = $options['redirect_url'];
	$silverbullet_rules = "\n";
	if ( $options['protect_login'] == 1 || $options['protect_login'] == '1' ) {
		$silverbullet_rules .= 'Redirect 301 '.$home_root.'wp-login.php '.$redirect_url."\n";
	}
	if ( $options['protect_comments'] == 1 || $options['protect_comments'] == '1' ) {
		$silverbullet_rules .= 'Redirect 301 '.$home_root.'wp-comments-post.php '.$redirect_url."\n";
	}
	if ( $options['protect_trackbacks'] == 1 || $options['protect_trackbacks'] == '1' ) {
		$silverbullet_rules .= 'Redirect 301 '.$home_root.'wp-trackback.php '.$redirect_url."\n";
	}
	if ( $options['protect_pingbacks'] == 1 || $options['protect_pingbacks'] == '1' ) {
		$silverbullet_rules .= 'Redirect 301 '.$home_root.'xmlrpc.php '.$redirect_url."\n";
	}
	
	/*
	 * If the file doesn't already exist check for write access to the directory
	 * and whether we have some rules. Else check for write access to the file.
	 */
	if ((!file_exists($htaccess_file) && is_writable($home_path) && $wp_rewrite->using_mod_rewrite_permalinks()) || is_writable($htaccess_file)) {
		$rules = explode( "\n", $silverbullet_rules );
		return silverbullet_insert_with_markers( $htaccess_file, $rules );
	}
}


function silverbullet_update_robots_txt( $options ) {
	global $wp_rewrite;

	$home_path = get_home_path();
	$robots_txt_file = $home_path.'robots.txt';

	//$existing_rules  = array_filter( extract_from_markers( $robots_txt_file, 'SBP' ) );
	//$new_rules = array_filter( explode( "\n", $wp_rewrite->mod_rewrite_rules() ) );

	$home_root = parse_url(home_url());
	if ( isset( $home_root['path'] ) ) {
		$home_root = trailingslashit($home_root['path']);
	} else {
		$home_root = '/';
	}

	$silverbullet_rules = "\n";
	if ( !empty( $options['general_rules'] ) ) {
		$silverbullet_rules .= $options['general_rules']."\n"."\n";
	}
	if ( $options['block_google'] == 1 || $options['block_google'] == '1' ) {
		$silverbullet_rules .= 'User-agent: googlebot'."\n";
		$silverbullet_rules .= 'User-agent: google'."\n";
		$silverbullet_rules .= 'Disallow: /'."\n"."\n";
	}
	if ( $options['block_bing'] == 1 || $options['block_bing'] == '1' ) {
		$silverbullet_rules .= 'User-agent: bingbot'."\n";
		$silverbullet_rules .= 'User-agent: msnbot'."\n";
		$silverbullet_rules .= 'Disallow: /'."\n"."\n";
	}
	if ( $options['block_yahoo'] == 1 || $options['block_yahoo'] == '1' ) {
		$silverbullet_rules .= 'User-agent: slurp'."\n";
		$silverbullet_rules .= 'User-agent: yahoo'."\n";
		$silverbullet_rules .= 'Disallow: /'."\n"."\n";
	}
	if ( $options['block_ask'] == 1 || $options['block_ask'] == '1' ) {
		$silverbullet_rules .= 'User-agent: askjeeves'."\n";
		$silverbullet_rules .= 'User-agent: jeeves'."\n";
		$silverbullet_rules .= 'User-agent: teoma'."\n";
		$silverbullet_rules .= 'Disallow: /'."\n"."\n";
	}
	if ( $options['block_baidu'] == 1 || $options['block_baidu'] == '1' ) {
		$silverbullet_rules .= 'User-agent: baiduspider'."\n";
		$silverbullet_rules .= 'User-agent: baidu'."\n";
		$silverbullet_rules .= 'Disallow: /'."\n"."\n";
	}
	if ( $options['block_yandex'] == 1 || $options['block_yandex'] == '1' ) {
		$silverbullet_rules .= 'User-agent: yandex'."\n";
		$silverbullet_rules .= 'Disallow: /'."\n"."\n";
	}
	
	/*
	 * If the file doesn't already exist check for write access to the directory
	 * and whether we have some rules. Else check for write access to the file.
	 */
	if ((!file_exists($robots_txt_file) && is_writable($home_path) && $wp_rewrite->using_mod_rewrite_permalinks()) || is_writable($robots_txt_file)) {
		$rules = explode( "\n", $silverbullet_rules );
		return silverbullet_insert_with_markers( $robots_txt_file, $rules );
	}
}


if( !function_exists( 'untrailingslashit' ) ) {
	function untrailingslashit( $string ) {
		return rtrim( $string, '/\\' );
	}
}

if( !function_exists( 'trailingslashit' ) ) {
	function trailingslashit( $string ) {
		return untrailingslashit( $string ) . '/';
	}
}

function silverbullet_clear_robots_txt() {
	global $wp_rewrite;

	//$home_path = get_option( 'home' ); //get_home_path();
	$home_path = ABSPATH;
	$robots_txt_file = $home_path.'robots.txt';

	//$existing_rules  = array_filter( extract_from_markers( $robots_txt_file, 'SBP' ) );
	//$new_rules = array_filter( explode( "\n", $wp_rewrite->mod_rewrite_rules() ) );

	$home_root = parse_url( get_option( 'home' ) /*home_url()*/ );
	if ( isset( $home_root['path'] ) ) {
		$home_root = trailingslashit($home_root['path']);
	} else {
		$home_root = '/';
	}

	$silverbullet_rules = "\n";
	
	/*
	 * If the file doesn't already exist check for write access to the directory
	 * and whether we have some rules. Else check for write access to the file.
	 */
	if ((!file_exists($robots_txt_file) && is_writable($home_path) && $wp_rewrite->using_mod_rewrite_permalinks()) || is_writable($robots_txt_file)) {
		$rules = explode( "\n", $silverbullet_rules );
		return silverbullet_insert_with_markers( $robots_txt_file, $rules );
	}
}


function silverbullet_checkbox_default( $value = 0 ) {
	$output = 'disabled';
	if ( $value == 1 || $value == '1' || $value == true ) {
		$output = 'enabled';
	}
	return $output;
}


function silverbullet_comment_line_in_file( $search_string, $file_path = null ) {
	if ($file_path === null) { // set by default; not possible to set in func declaration
		$file_path = get_home_path().'wp-settings.php';
	}
	//$file_resource = fopen($file_path, 'r+');
	$content = '';
	
	/*while( !feof($file_resource) ) {
		$line = fgets($file_resource);
		$found_position = strpos( $line, $search_string );
		$found_position_commented = strpos( $line, '//' );
		
		if ( $found_position !== false && $found_position_commented === false ) {
			//$line = "\n".'//11'."\n".'//22'.'//'.$line.'//33'."\n";
			$line = '//'.$line;
		}
		$content .= $line;
		//$content.= "\r\n";
		//$content.= "\n";
	}*/
	
	$lines = file( $file_path ); // array of lines
	foreach ( $lines as $line_num => $line ) { // line by line
		$found_position = strpos( $line, $search_string );
		
		if ( $found_position !== false ) { // line found
			$found_position_commented = strpos( $line, '//' );
			if ( $found_position_commented === false ) { // line is already commented
				//$line = "\n".'//11'."\n".'//22'.'//'.$line.'//33'."\n";
				$line = '//'.$line;
			}
		}
		$content .= $line;
		
		//echo "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br />\n";
	}

	file_put_contents( $file_path, $content );
	//fclose($file_resource);
}


function silverbullet_uncomment_line_in_file( $search_string, $file_path = null ) {
	//$home_url = get_option('siteurl'); // get_home_path()
	if ($file_path === null) { // set by default; not possible to set in func declaration
		$file_path = get_home_path().'wp-settings.php';
	}
	
	$content = '';
	$lines = file( $file_path ); // array of lines
	foreach ( $lines as $line_num => $line ) { // line by line
		$found_position = strpos( $line, $search_string );
		
		if ( $found_position !== false ) { // line found
			$found_position_commented = strpos( $line, '//' );
			if ( $found_position_commented === 0 ) { // line is not yet commented and comment is located at the beginning of the string
				$line = substr( $line, 2 ); // uncomment
			}
		}
		
		$content .= $line;
	}

	file_put_contents( $file_path, $content );
}


function silverbullet_settings_intro_page() {
	?>
	<div class="wrap">

		<h2><span class="dashicons dashicons-shield" style="position: relative; top: 5px;"></span> Silver Bullet</h2>
		
		<p>The plugin will help you to speedup your website.</p>

		<h3>Plugin's features:</h3>
		
		<ul style="list-style: square; margin-left: 30px;">
			<li>speedup website by not loading not needed functions (It saves about 2% on memory which WordPress consumes on every page load)</li>
			<li>shows in the admin bar the number of SQL queries during the WordPress execution, the amount of time in seconds to generate the page and memory load</li>
		</ul>
		
		<h3 id="id-every-page-load">"Every page load" meaning:</h3>
		
		<ul style="list-style: square; margin-left: 30px;">
			<li><strong>User opens the page via browser.</strong></li>
			<li><strong>Search engine is crawling your website.</strong> It can be Google, Bing, Baidu or any other.</li>
			<li><strong>Brute-forcer sends request</strong> to your login form to hack password for username on your website. Mostly "admin" username is used by spammers.</li>
			<li><strong>Spammer sends request</strong> to the comments form on your website.</li>
		</ul>
		
		On every request from the list above WordPress is executed and it consumes server resources.
		
		<h3>Additional security recomedations:</h3>
		
		<ul style="list-style: square; margin-left: 30px;">
			<li><strong>Keep WordPress, themes and plugins up to date.</strong> Latest versions are more secure and have fewer issues.</li>
			<li><strong>Delete unused plugins.</strong> All activated plugins are consuming memory on every page load.
				Delete or at least temporary disable unused plugins.</li>
			<li><strong>Do not include common English words as parts of your passwords.</strong> Even replacing 'a' with '@' is pretty easy to guess. For example: "p@$5w0rd" or "s3cur1ty" are very weak and common passwords. Try to use non-existing words or words from other languages.</li>
			<li><strong>Delete "admin" username from your website.</strong> The majority of brute-force attacks are targetted to the "admin" usernames. Same relates to such common usernames as: administrator, author, editor or user.</li>
			<li><strong>Install caching plugin</strong>. If your website is slow then you may try to use <a href="https://wordpress.org/plugins/wp-super-cache/" target="_blank">WP Super Cache</a> plugin.</li>
		</ul>
		
	</div><!-- .wrap -->
	<?php
}
