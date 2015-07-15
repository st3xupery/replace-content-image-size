<?php
/*
Plugin Name: Replace Content Image Size
Plugin URI: http://blogestudio.com
Description: Find images displayed in posts content and change the format size, very useful when you change the blog theme.
Version: 1.2.1
Author: Pau Iglesias, Blogestudio
License: GPLv2 or later
*/

// Avoid direct script calls via plugin URL
if (!function_exists('add_action'))
	die;

// Check admin area
if (!is_admin())
	return;

/**
 * Replace Content Image Size plugin class
 *
 * @package WordPress
 * @subpackage Replace Content Image Size
 */

// Avoid declaration plugin class conflicts
if (!class_exists('BE_Replace_Content_Image_Size')) {

	// Create object plugin
	add_action('init', array('BE_Replace_Content_Image_Size', 'instance'));

	// Main class
	class BE_Replace_Content_Image_Size {


	
		// Properties
		// ---------------------------------------------------------------------------------------------------



		// Plugin key
		private $key = 'replace-content-image-size';



		// Initialization
		// ---------------------------------------------------------------------------------------------------



		/**
		 * Creates a new object instance
		 */
		public static function instance() {
			return new BE_Replace_Content_Image_Size;
		}



		/**
		 * Constructor
		 */
		private function __construct() {
			
			// Set Tools submenu
			add_action('admin_menu', array(&$this, 'admin_menu'));
		}



		/**
		 *  Load translation file
		 */
		private function load_plugin_textdomain($lang_dir = 'languages') {
			
			// Check load
			static $loaded;
			if (isset($loaded))
				return;
			$loaded = true;
			
			// Check if this plugin is placed in wp-content/mu-plugins directory or subdirectory
			if (('mu-plugins' == basename(dirname(__FILE__)) || 'mu-plugins' == basename(dirname(dirname(__FILE__)))) && function_exists('load_muplugin_textdomain')) {
				load_muplugin_textdomain($this->key, ('mu-plugins' == basename(dirname(__FILE__))? '' : basename(dirname(__FILE__)).'/').$lang_dir);
			
			// Usual wp-content/plugins directory location
			} else {
				load_plugin_textdomain($this->key, false, basename(dirname(__FILE__)).'/'.$lang_dir);
			}
		}



		// Hooks
		// ---------------------------------------------------------------------------------------------------



		/**
		 * Admin menu hook
		 */
		public function admin_menu() {
			add_submenu_page('tools.php', 'Replace Content Image Size', 'Replace Content Image Size', 'manage_options', $this->key, array(&$this, 'replace'));
		}



		// Internal methods
		// ---------------------------------------------------------------------------------------------------



		/*
		 * Main method: show the form, list of founded images and confirmation submit button, and update posts table
		 */
		public function replace() {
			
			// Load translations
			$this->load_plugin_textdomain();
			
			// Track errors
			$form_errors = array();
			
			// Check step
			$step = 0;
			if (isset($_POST['hd-submit'])) {
				$step = intval($_POST['hd-submit']);
				if ($step < 0 || $step > 2)
					$step = 0;
			}
			
			// Validate form
			if ($step > 0) {
				
				// Check period width
				$width_usr = $_POST['tx-width'];
				$widths = explode('-', $width_usr);
				if (!(count($widths) == 1 || count($widths) == 2)) {
					$width = '';
					$step = 0;
					$form_errors['width'] = __('Incorrect Width format', $this->key);
				
				// Period or single
				} else {
				
					// Check width
					$width = intval(trim($widths[0]));
					if (!$width > 0) {
						$width = '';
						$step = 0;
						$form_errors['width'] = __('Incorrect Width value', $this->key);
					}
					
					// Period widths					
					if (count($widths) == 2) {
						$width2 = intval(trim($widths[1]));
						if (!$width2 > 0 || !($width2 > $width)) {
							$width = '';
							$step = 0;
							$form_errors['width'] = __('Incorrect second Width value', $this->key);
						} elseif ($width2 - $width > 100) {
							$width2 = $width + 100;
						}
					}
				}
				
				// Check size
				$size = trim($_POST['tx-size']);
				if (empty($size)) {
					$step = 0;
					$form_errors['size'] = __('Please, enter a format size', $this->key);
				}

				// Check default post type
				$post_type = trim($_POST['tx-post-type']);
				if (empty($post_type)) {
					if ($step > 0)
						$post_type = 'post';
				}
			}
			
			?>
		
			<div class="wrap">
				
				<?php screen_icon('tools'); ?><h2>Replace Content Image Size</h2>
				
				<form method="post" style="padding: 15px;">

					<?php if ($step == 0) : ?>
							
						<h3><?php _e('Step 1/3', $this->key); ?></h3>
						
						<p><i>&rarr; <?php _e('Input data', $this->key); ?></i></p>
	
						<input type="hidden" name="hd-submit" value="1" />
						
						<label for="tx-width"><?php _e('Width: exact or period with "-" and max 100 units', $this->key); ?></label><br />
						<input type="text" name="tx-width" id="tx-width" value="<?php echo isset($width_usr)? esc_attr($width_usr) : ''; ?>" class="regular-text" /><br />
						<?php if (isset($form_errors['width'])) : ?><span style="color: red; ?"><?php echo $form_errors['width']; ?></span><br /><?php endif; ?>
						<br />
						
						<label for="tx-size"><?php _e('New size: thumbnail, medium, large, full or custom', $this->key); ?></label><br />
						<input type="text" name="tx-size" id="tx-size" value="<?php echo isset($size)? esc_attr($size) : ''; ?>" class="regular-text" /><br />
						<?php if (isset($form_errors['size'])) : ?><span style="color: red; ?"><?php echo $form_errors['size']; ?></span><br /><?php endif; ?>
						<br />
						
						<label for="tx-post-type"><?php _e('Post type: optional, empty for <i>post</i>', $this->key); ?></label><br />
						<input type="text" name="tx-post-type" id="tx-post-type" value="<?php echo isset($post_type)? esc_attr($post_type) : ''; ?>" class="regular-text" /><br /><br />
						
						<input type="submit" value="<?php _e('Next step: check and confirm', $this->key); ?>" class="button-primary" />
						
					<?php elseif ($step == 1) : ?>

						<h3><?php _e('Step 2/3', $this->key); ?></h3>
						
						<p><i>&rarr; <?php _e('Confirm replacements', $this->key); ?></i></p>
				
						<input type="hidden" name="hd-submit" value="2" />						
						<input type="hidden" name="tx-width" value="<?php echo esc_attr($width_usr); ?>" />
						<input type="hidden" name="tx-size" value="<?php echo esc_attr($size); ?>" />
						<input type="hidden" name="tx-post-type" value="<?php echo esc_attr($post_type); ?>" />
						
						<p><?php _e('Old width', $this->key); ?>: <b><?php echo esc_html($width_usr); ?></b></p>
						
						<p><?php _e('New size', $this->key); ?>: <b><?php echo esc_html($size); ?></b></p>
						
						<p><?php _e('Post type', $this->key); ?>: <b><?php echo esc_html($post_type); ?></b></p>
					
					<?php elseif ($step == 2) : ?>
					
						<h3><?php _e('Step 3/3', $this->key); ?></h3>
							
					<?php endif; ?>



					<?php
					
					/* Step 2 Or 3 */
					
					if ($step > 0) :

						// Globals
						global $wpdb;
						
						// Mod flag
						$mod_sum = false;

						// Exact results						
						if (!isset($width2))
							$width2 = $width;
						
						// Enum widths
						$ids = array();
						$posts = array();
						for ($w = $width; $w <= $width2; $w++) {
							$entries = $wpdb->get_results('SELECT ID, post_title, post_content FROM '.$wpdb->posts.' WHERE post_content LIKE "%<img %" AND (post_content LIKE "%-'.esc_sql($w).'x%" OR post_content LIKE '."'".'%width="'.$w.'"%'."'".') AND post_type = "'.esc_sql($post_type).'" AND post_status IN ("publish", "future")'.((count($ids) > 0)? ' AND ID NOT IN ('.implode(',', $ids).')' : '').' ORDER BY ID DESC');
							if ($entries && count($entries) > 0) {
								foreach ($entries as $entry) {
									$posts[] = $entry;
									$ids[] = $entry->ID;
								}
							}
						}						
						
						// Check results
						if ($posts && count($posts) > 0) {
							
							// Enum posts
							foreach ($posts as $post) {
								
								// Analyze
								$i = 0;
								$mod = false;
								$id_displayed = false;
								$content = '';
								$chunks = explode("\n", $post->post_content);
								$slug_checked = false;								
								foreach ($chunks as $chunk) {
									
									// Order
									$i++;
									
									// Check image tag and confirm database search
									if (stripos($chunk, '<img ') !== false) {
									
										// Enum sizes
										for ($w = $width; $w <= $width2; $w++) {
									
											// Check size in chunk interval
											if (stripos($chunk, '-'.$w.'x') > 0 || stripos($chunk, 'width="'.$w.'"') > 0) {
										
												// Chunk attachment
												$attachment = null;


												/* 1. Find by guid */
												
												// Search src code
												$pos1 = stripos($chunk, 'src="');
												if ($pos1 > 0) {
													$pos1 += 5;
													$pos2 = stripos($chunk, '"', $pos1 + 1);
													if ($pos2 > 0) {
														
														// Show post info
														$this->display_post_info($post, $step, $id_displayed);
														
														// Clean URL
														$url = urldecode(substr($chunk, $pos1, $pos2 - $pos1));
														if (preg_match('/^(.*)-[\d]+x[\d]+\.(.*)$/', $url, $matches) == 1)
															$url = $matches[1].'.'.$matches[2];
														
														// Search guid
														$result = $wpdb->get_results('SELECT ID FROM '.$wpdb->posts.' WHERE guid = "'.esc_sql($url).'" AND post_type = "attachment"');
														if ($result && is_array($result) && count($result) > 0) {
															
															// Search attachments
															$attachments = get_posts(array('p' => $result[0]->ID, 'post_type' => 'attachment'));
															if ($attachments && is_array($attachments) && count($attachments) > 0) {
																$attachment = $attachments[0];
																if ($step == 1)
																	echo '<p>&raquo; '.sprintf(__('Found guid: <b>%s</b>', $this->key), $url).'</p>';
															}
														}

														// Check log
														if (!isset($attachment) && $step == 1) {
															echo '<p>&raquo; '.sprintf(__('No guid found: %s', $this->key), $url).'</p>';
														}
													}
												}


												/* 2. Find by attachment slug */
												
												// Check previous search
												if (!isset($attachment)) {
													$pos1 = stripos($chunk, '-'.$w.'x');
													if ($pos1 > 0) {
														
														// Check for slug delimiter
														$pos0 = strrpos(substr($chunk, 0, $pos1), '/');
														if ($pos0 > 0) {
															
															// Show post info
															$this->display_post_info($post, $step, $id_displayed);
															
															// Slug
															$slug = substr($chunk, $pos0 + 1, $pos1 - $pos0 - 1);
															$slug = strtolower($this->remove_accents($slug));
															
															$attachments = get_posts(array('name' => $slug, 'post_type' => 'attachment'));
															
															// Check attachment
															if ($attachments && is_array($attachments) && count($attachments) > 0) {
																$attachment = $attachments[0];
																if ($step == 1)
																	echo '<p>&raquo; '.sprintf(__('Found slug: <b>%s</b>', $this->key), $slug).'</p>';
															
															// Check log
															} elseif ($step == 1)
																echo '<p>&raquo; No slug found: '.$slug.'</p>';

															$slug_checked = true;
														}
													}
												}


												/* 3. Find by attachment src */
												
												// Check previous search
												if (!isset($attachment) && !$slug_checked) {
													$pos1 = stripos($chunk, 'src="');
													if ($pos1 > 0) {
														$pos1 += 5;
														$pos2 = strpos($chunk, '"', $pos1 + 1);
														if ($pos2 > 0) {
															$url = substr($chunk, $pos1, $pos2 - $pos1);
															$pos = strrpos($url, '/');
															if ($pos > 0) {
																$url = substr($url, $pos + 1);
																$pos = strrpos($url, '.');
																if ($pos > 0) {
																	
																	// Show post info
																	$this->display_post_info($post, $step, $id_displayed);
																	
																	$slug = substr($url, 0, $pos);
																	$slug = strtolower($this->remove_accents($slug));
																	$attachments = get_posts(array('name' => $slug, 'post_type' => 'attachment'));
																	
																	// Check attachment
																	if ($attachments && is_array($attachments) && count($attachments) > 0) {
																		$attachment = $attachments[0];
																		if ($step == 1)
																			echo '<p>&raquo; '.sprintf(__('Found slug: <b>%s</b>', $this->key), $slug).'</p>';
																	
																	// Check log
																	} elseif ($step == 1)
																		echo '<p>&raquo; '.sprintf(__('No slug found: %s', $this->key), $slug).'</p>';
																}
															}
														}
													}
												}


												// Check object
												if (isset($attachment)) {

													// Get image info
													$image = wp_get_attachment_image_src($attachment->ID, $size);
													if ($image) {
														
														// Copy mod
														$mod_old = $mod;
													
														// Chunk edit
														$mod = true;
														$mod_sum = true;
														
														// Copy chunk
														$chunk_old = $chunk;
														
														// Replace data
														$chunk = preg_replace('/src=".*"/Ui', 'src="'.$image[0].'"', $chunk);
														$chunk = preg_replace('/width=".*"/Ui', 'width="'.$image[1].'"', $chunk);
														$chunk = preg_replace('/height=".*"/Ui', 'height="'.$image[2].'"', $chunk);
														$chunk = preg_replace('/class=\"(.*)size-[_a-zA-Z0-9-]*?([^\"]*)\"/Ui', 'class="'.'$1'.'size-'.$size.'$2'.'"', $chunk);
														
														// Set checkbox name
														$checkbox_name = 'ck-resize-'.$post->ID.'-'.$i;
														
														// Check step
														if ($step == 1) {
															
															// Comparison and replacement
															echo '<p>&raquo; Replacement:</p>';

															echo '<div style="margin-bottom: 15px;">';

																echo '<div style="float: left; width: 30px;"><input type="checkbox" checked name="'.$checkbox_name.'" id="'.$checkbox_name.'" value="1" style="float: let; display: inline;" /></div>';

																// Old version and new fragment			
																echo '<p style="float: left; width: 50%; padding-top: 0; margin-top: 0;"><span style="color: grey;">'.htmlspecialchars($chunk_old, ENT_QUOTES).'</span><br /><br />';
																echo htmlspecialchars($chunk, ENT_QUOTES).'</p>';
																
																echo '<p style="float: left; width: 40%; padding-top: 0; margin-top: 0;"><img src="'.str_replace('.dev', '.es', $image[0]).'" /></p>';
																
																echo '<br style="clear: left;" />';

															echo '</div>';
														}
														
														// Check chunk modified
														elseif (!(isset($_POST[$checkbox_name]) && $_POST[$checkbox_name] == '1')) {
															$mod = $mod_old;
															$chunk = $chunk_old;
														}
													}

													// Exit for
													break;
												}
											}
										}
									}
									
									// Re-compose content
									$content .= (($content !== '')? "\n" : '').$chunk;
								}
								
								// Check for post updates
								if ($mod && $step == 2) {
									$wpdb->query('UPDATE '.$wpdb->posts.' SET post_content = "'.esc_sql($content).'" WHERE ID = '.$post->ID);
									clean_post_cache($post->ID);
								}
							}
						}
						
						if ($step == 1) {
							if (!$mod_sum) {
								echo '<p>'.__('Nothing to do', $this->key).'</p>';
							} else {
								echo '<hr />';
								echo '<p>&nbsp;</p>';
								echo '<p>'.sprintf(__('Please, before next step, <b>BACKUP TABLE %s</b>', $this->key), $wpdb->posts).'</p>';
								echo '<input type="submit" value="'.__('Next step: UPDATE !', $this->key).' "class="button-primary" />';
								echo '<p>&nbsp;</p>';
							}
						
						
						} elseif ($step == 2) {
							echo '<p><i>&rarr; '.__('Data updated', $this->key).'</i></p>';
						}
					
					endif;
				
				?></form>
				
			</div><?php
		}



		/*
		 * Show posts info: Id and links to single post and edit form
		 */
		private function display_post_info($post, $step, &$id_displayed) {
			if ($step == 1 && !$id_displayed) {
				echo '<hr /><p style="line-height: 20px;">Post <b>'.$post->ID.'</b><br /><a href="'.get_permalink($post->ID).'" target="post_display">'.$post->post_title.'</a> &nbsp; [<a href="'.get_edit_post_link($post->ID).'" target="post_edit">'.__('Edit entry', $this->key).'</a>]</p>';
				$id_displayed = true;
			}
		}



		/*
		 * Helper function for slug composition
		 */
		private function remove_accents($value)	{
			return(utf8_encode(strtr(utf8_decode($value), utf8_decode("ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÒÓÔÕÖØÙÚÛÜÝßÑàáâãäåæçèéêëìíîïðòóôõöøùúûüýÿñ"),
														  utf8_decode("SOZsozYYuAAAAAAACEEEEIIIIDOOOOOOUUUUYsNaaaaaaaceeeeiiiiooooooouuuuyyn"))));
		}



	}
}