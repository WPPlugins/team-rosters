<?php
/*-----------------------------------------------------------
 *	MSTW-TR-UTILITY-FUNCTIONS.PHP
 *		Utility or convenience functions used in both the front and back ends
 *
 *	MSTW Wordpress Plugins (http://shoalsummitsolutions.com)
 *	Copyright 2014-17 Mark O'Donnell (mark@shoalsummitsolutions.com)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.

 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program. If not, see <http://www.gnu.org/licenses/>.
 *-------------------------------------------------------------------------*/
 
 /*------------------------------------------------------------------------
 *	MSTW-TR-UTILITY-FUNCTIONS
 *	These functions are included in both the front and back end.
 *
 * 0. mstw_tr_log_msg - logs messages to /wp-content/debug IF WP_DEBUG is true
 *
 * 1. mstw_tr_safe_ref - prevents uninitialized string errors 
 *
 * 2. mstw_tr_get_defaults - returns the default mstw_tr_options[]
 *
 * 2.1 mstw_tr_get_data_fields_columns_defaults - returns the defaults 
 *		for the data fields & columns options (ONLY)
 *
 * 2.2 mstw_tr_get_roster_table_defaults - returns the defaults 
 *		the roster table options (ONLY)
 *
 * 2.3 mstw_tr_get_roster_table_colors_defaults -  returns the defaults 
 *		for the table colors options (ONLY)
 *
 * 2.4 mstw_tr_get_bio_gallery_defaults - returns the defaults 
 *		for the player profile & team gallery (ONLY)
 *
 * 6. mstw_tr_set_fields_by_format - returns wp_parse_args( $settings, $defaults )
 *		NEED TO RECONCILE THIS WITH #6 above
 *
 * 7. mstw_tr_build_gallery() - Builds the player gallery on the front end.
 *
 * 8. mstw_tr_build_player_photo - Constructs the html for the player profile 
 *		player photo.
 *
 * 9. mstw_tr_build_player_name - constructs a player name based
 *		on first, last, and $options['name_format']
 *
 * 10. mstw_tr_build_profile_logo - Constructs the html for the player profile
 *		logo.
 *
 * 11. mstw_tr_build_bats_throws - constructs the html for the 
 *		bats-throws field ('B/T') in all displays 
 *
 * 13. mstw_tr_admin_notice: displays team rosters admin notices 
 *
 * 13.1. mstw_tr_add_admin_notice - Adds admin notices to transient for display 
 *
 * 14. mstw_tr_build_colors_html - builds the HTML for team colors
 *		when the 'use team colors' display option is set 
 *
 * 15. mstw_tr_build_hidden_fields - builds the hidden fields for the 
 *		JavaScript to use when using the teams DB colors.
 *
 * 16. mstw_tr_find_team_in_plugin - Determines if a team is linked to a team 
 *		in the S&S or LM plugin DB
 *
 * 17. mstw_tr_build_team_logo: Builds the HTML for a team logo 
 *
 * 20. mstw_tr_get_current_team: Returns the current team (slug)
 *
 * 21. mstw_tr_set_current_team: Sets the current team (slug) in the options DB 
 *
 * 22. mstw_tr_help_sidebar: sets the WP (context sensitive) help sidebar for all TR screens
 *
 * 40. mstw_tr_build_admin_edit_screen - Convenience function to build admin UI data entry screens
 *
 * 41. mstw_tr_build_admin_edit_field - Helper function for building admin fields
 *
 * 42. mstw_tr_build_settings_screen - builds admin settings form (using settings api) 
 *
 * 43. mstw_tr_build_settings_field - builds admin screen form fields
 *
 *--------------------------------------------------------------------------*/
 
//------------------------------------------------------------------------------
//	0. mstw_tr_log_msg - logs messages to /wp-content/debug IF WP_DEBUG is true
//		ARGUMENTS:
//			$msg - string, array, or object to log
//					note: if $msg == 'divider' a divider is output to the log
//		RETURNS:
//			None. Outputs to WP error_log
//
if ( !function_exists( 'mstw_tr_log_msg' ) ) {
	function mstw_tr_log_msg( $msg ) {
		if ( WP_DEBUG === true ) {
			if ( $msg == 'divider' ) {
				error_log( '------------------------------------------------------' );
			}
			else if( is_array( $msg ) || is_object( $msg ) ) {
				error_log( print_r( $msg, true ) );
			} 
			else {
				error_log( $msg );
			}
		}
	} //End: mstw_tr_log_msg( )
}

// ----------------------------------------------------------------
// 1. mstw_tr_safe_ref - prevents uninitialized string errors 
//		Arguments:
//			$array - the array to reference
//			$index - the index into $array
//		Returns;
//			$array[$index] if it is set, '' otherwise
//
if( !function_exists( 'mstw_tr_safe_ref' ) ) {
	function mstw_tr_safe_ref( $array, $index ) {
		return ( isset( $array[$index] ) ? $array[$index] : '' );
	}
}

 //-----------------------------------------------------------
 //	2. mstw_tr_get_defaults: returns the array of ALL option defaults
 //	
 if ( !function_exists( 'mstw_tr_get_defaults' ) ) {
	function mstw_tr_get_defaults( ) {
		//Base defaults
		$defaults = array_merge( mstw_tr_get_data_fields_columns_defaults( ),
								 mstw_tr_get_roster_table_defaults( ),
								 mstw_tr_get_roster_table_colors_defaults( ),
								 mstw_tr_get_bio_gallery_defaults( )
								 );
				
		return $defaults;
	} //End: mstw_tr_get_defaults()
}

 //-----------------------------------------------------------
 //	2.1 mstw_tr_get_data_fields_columns_defaults: returns the array 
 //		defaults for the data fields & columns options (ONLY)
 //
 if ( !function_exists( 'mstw_tr_get_data_fields_columns_defaults' ) ) {
	function mstw_tr_get_data_fields_columns_defaults( ) {
		// "show_" arguments are all 0|1  (hide|show)
		$defaults = array(	
				'show_number'			=> 1,
				'number_label'			=> __( 'Nbr', 'team-rosters' ),
				//always show the name
				'name_label'			=> __( 'Name', 'team-rosters' ),
				'show_photos'			=> 0,
				'photo_label'			=>  __( 'Photo', 'team-rosters' ),
				'show_position'			=> 1,
				'position_label'		=> __( 'Pos', 'team-rosters' ),
				'show_height'			=> 1,
				'height_label'			=> __( 'Ht', 'team-rosters' ),
				'show_weight'			=> 1,
				'weight_label'			=> __( 'Wt', 'team-rosters' ),
				'show_year'				=> 0,
				'year_label'			=> __( 'Year', 'team-rosters' ),
				'show_experience'		=> 0,
				'experience_label'		=> __( 'Exp', 'team-rosters' ),
				'show_age'				=> 0,
				'age_label'				=> __( 'Age', 'team-rosters' ),
				'show_home_town'		=> 0,
				'home_town_label'		=> __( 'Home Town', 'team-rosters' ),
				'show_last_school'		=> 0,
				'last_school_label'		=> __( 'Last School', 'team-rosters' ),
				'show_country'			=> 0,
				'country_label'			=> __( 'Country', 'team-rosters' ),
				'show_bats_throws'		=> 0,
				'bats_throws_label'		=> __( 'Bat/Thw', 'team-rosters' ),
				'show_other_info'		=> 0,
				'other_info_label'		=> __( 'Other', 'team-rosters' ),
				);
				
		return $defaults;
	} //End: mstw_tr_get_data_fields_columns_defaults
 }
 
 //-----------------------------------------------------------
 //	2.2 mstw_tr_get_roster_table_defaults: returns the defaults for 
 //		the roster table options (ONLY)
 //
 if ( !function_exists( 'mstw_tr_get_roster_table_defaults' ) ) {
	function mstw_tr_get_roster_table_defaults( ) {
		$defaults = array(	
				'show_title'			=> 0, 
				// 0|1 (hide|show)
				'roster_type'			=> 'custom',
				// custom | high-school | college | pro
				// baseball-high-school | baseball-college | baseball-pro
				'links_to_profiles'		=> 1,
				// 0|1 (hide|show)
				'sort_order'			=> 'alpha', //sorts by last name
				// alpha|numeric 
				'sort_asc_desc'			=> 'asc',  //ascending order
				// asc|desc
				'name_format'			=> 'last-first',
				// last-first | first-last | first-only | last-only
				'table_photo_width'		=> '',
				'table_photo_height'	=> '',
				);
				
		return $defaults;
	} //End: mstw_tr_get_roster_table_defaults
 }

 //-----------------------------------------------------------
 //	2.3 mstw_tr_get_roster_table_colors_defaults: returns the array 
 //		defaults for the table colors options (ONLY)
 //
 if ( !function_exists( 'mstw_tr_get_roster_table_colors_defaults' ) ) {
	function mstw_tr_get_roster_table_colors_defaults( ) {
		$defaults = array(	
				'use_team_colors'		=> 0,
				'table_title_color'		=> '',
				'table_links_color'		=> '',
				'table_head_bkgd'		=> '',
				'table_head_text'		=> '',
				'table_even_row_bkgd'	=> '',
				'table_even_row_text'	=> '',
				'table_odd_row_bkgd'	=> '',
				'table_odd_row_text'	=> '',
				'table_border_color'	=> '',
				);
				
		return $defaults;
	} //End: mstw_tr_get_roster_table_colors_defaults
 }
 
 
 //-----------------------------------------------------------
 //	2.4 mstw_tr_get_bio_gallery_defaults: returns the array 
 //		defaults for the player profile & team gallery (ONLY)
 //
 if ( !function_exists( 'mstw_tr_get_bio_gallery_defaults' ) ) {
	function mstw_tr_get_bio_gallery_defaults( ) {
		$defaults = array(
				'sp_show_title'			=> 1,
				'sp_content_title'		=> __( 'Player Bio', 'team-rosters' ),
				
				'sp_use_team_colors'	=> 0,
				
				'sp_image_width'		=> 150,
				'sp_image_height'		=> 150,
		
				'sp_main_bkgd_color'	=> '',
				'sp_main_text_color'	=> '',
				'sp_bio_border_color'	=> '',
				'sp_bio_header_color'	=> '',
				'sp_bio_bkgd_color'		=> '',
				'sp_bio_text_color'		=> '',
				'gallery_links_color'	=> '',
				);
				
		return $defaults;
	} //End: mstw_tr_get_bio_gallery_defaults( )
 }

//-----------------------------------------------------------
// 6. mstw_tr_set_fields_by_format - returns wp_parse_args( $settings, $defaults )
//		NEED TO RECONCILE THIS WITH #6 above
//	  ARGUMENTS:
//		$format: roster format
//	  RETURNS:
//		$settings: default settings for the specified format
//
 if ( !function_exists( 'mstw_tr_set_fields_by_format' ) ) {
	function mstw_tr_set_fields_by_format( $format ) {
		
		return mstw_tr_get_fields_by_roster_type( $format );
		
	} //End: mstw_tr_set_fields_by_format()
 }

 //-----------------------------------------------------------
 // 6. mstw_tr_get_fields_by_roster_type - Sets the show/hide fields 
 //		based on the 
 //		roster_type argument: custom, high-school, college, pro, 
 //		baseball-high-school, baseball-college, or baseball-pro. 
 //		"custom" causes the Settings admin page defaults to be used
 //
 if ( !function_exists( 'mstw_tr_get_fields_by_roster_type' ) ) {
	function mstw_tr_get_fields_by_roster_type( $roster_type ) {
		
		$show_bats_throws = ( false === strpos( $roster_type, 'baseball' ) ) ? 0 : 1;

		switch ( $roster_type ) {
			case 'baseball-high-school':
			case 'high-school':
				$settings = array(					
					'roster_type'			=> $roster_type,					
					'show_number'			=> 1,					
					'show_position'			=> 1,
					'show_height'			=> 1,
					'show_year'				=> 1,
					'show_experience'		=> 0,
					'show_age'				=> 0,
					'show_home_town'		=> 0,
					'show_last_school'		=> 0,
					'show_country'			=> 0,
					'show_bats_throws'		=> $show_bats_throws,
					'show_other_info'		=> 0,
				);
				break;
				
			case 'baseball-college':
			case 'college':
				$settings = array(	
					'roster_type'			=> $roster_type,
					'show_number'			=> 1,
					'show_position'			=> 1,
					'show_height'			=> 1,
					'show_year'				=> 1,
					'show_experience'		=> 1,
					'show_age'				=> 0,
					'show_home_town'		=> 1,
					//this is shown in Home Town(Last School) column
					'show_last_school'		=> 1,
					'show_country'			=> 0,
					'show_bats_throws'		=> $show_bats_throws,
					'show_other_info'		=> 0,
				);		
				break;
			
			case 'pro':
			case 'baseball-pro':
				$settings = array(	
					'roster_type'			=> $roster_type,
					'show_number'			=> 1,
					'show_position'			=> 1,
					'show_height'			=> 1,
					'show_year'				=> 0,
					'show_experience'		=> 1,
					'show_age'				=> 1,
					'show_home_town'		=> 0,
					'show_last_school'		=> 1,
					//show the country as part of the last_school(country) column
					//so don't need to set here
					'show_country'			=> 1,
					'show_bats_throws'		=> $show_bats_throws,
					'show_other_info'		=> 0,
				);
				break;
				
			default:  // custom roster type
				$settings = get_option( 'mstw_tr_options' );
				break;
		}
		return $settings;
	} //End: mstw_tr_get_fields_by_roster_type()
}

 //-----------------------------------------------------------
 //	7. mstw_tr_build_gallery: Builds the player gallery on the front end.
 //		Called by both the gallery shortcode and the team taxonomy 
 //		page template.
 //
 if ( !function_exists( 'mstw_tr_build_gallery' ) ) {
	function mstw_tr_build_gallery( $team_slug, $roster_type, $options ) {
		//mstw_tr_log_msg( 'in mstw_tr_build_gallery ... ' );
		//mstw_tr_log_msg( $options );

		// Set the sort field	
		switch ( $options['sort_order'] ) {
			case 'numeric':
				$sort_key = 'player_number';
				$order_by = 'meta_value_num';
				break;
			case 'alpha-first':
				$sort_key = 'player_first_name';
				$order_by = 'meta_value';
				break;
			default: // alpha by last
				$sort_key = 'player_last_name';
				$order_by = 'meta_value';
				break;
		}
		
		// Set sort order
		switch ( $options['sort_asc_desc'] ) {
			case 'desc':
				$sort_order = 'DESC';
				break;
			default:
				$sort_order = 'ASC';
				break;	
		}
			
		// Get the team roster		
		$posts = get_posts( array( 'numberposts' => -1,
								   'post_type' => 'mstw_tr_player',
								   'mstw_tr_team' => $team_slug, 
								   'orderby' => $order_by, 
								   'meta_key' => $sort_key,
								   'order' => $sort_order 
								));	
	
		if( $posts ) {
			// Set up the hidden fields for jScript CSS 
			$output = mstw_tr_build_team_colors_html( $team_slug, $options, 'gallery' );
			
			foreach( $posts as $post ) { // ( have_posts( ) ) : the_post();
				
				$output .= "<div class='player-tile player-tile_" . $team_slug . "'>\n";
		
				$output .= "<div class = 'player-photo' >\n";
					$output .= mstw_tr_build_player_photo( $post, $team_slug, $options, 'gallery' );
				$output .= "</div> <!-- .player-photo -->\n";
				
				$output .= "<div class = 'player-info-container'>\n";
					$output .= "<div class='player-name-number player-name-number_$team_slug'>\n"; 
						if ( $options['show_number'] ) {
							$player_number = get_post_meta($post->ID, 'player_number', true );
							$output .= "<div class='player-number'>$player_number</div>";
						}
						$player_name = mstw_tr_build_player_name( $post, $options, 'gallery' );
						$output .= "<div class='player-name'>$player_name</div>";
					$output .= "</div> <!-- .player-name-number -->\n";
					
					$output .= "<table class='player-info player-info_$team_slug'>\n";
					  $output .= "<tbody>\n";
						$row_start = '<tr><td class="lf-col">';
						$new_cell = ':</td><td class="rt-col">'; //colon is for the end of the title
						$row_end = '</td></tr>';
						
						// POSITION
						if( $options['show_position'] ) {
							$output .= $row_start . $options['position_label'] . $new_cell 
												  . get_post_meta($post->ID, 'player_position', true ) . $row_end;
						}

						// BATS/THROWS
						if( $options['show_bats_throws'] ) {
							$output .= $row_start . $options['bats_throws_label'] . $new_cell   
												  . mstw_tr_build_bats_throws( $post ) . $row_end;
						}
						
						// HEIGHT/WEIGHT
						if ( $options['show_weight'] and $options['show_height'] ) {
							$output .= $row_start . $options['height_label'] . '/' 
												  . $options['weight_label'] . $new_cell 
												  . get_post_meta($post->ID, 'player_height', true ) . '/' 
												  . get_post_meta($post->ID, 'player_weight', true ) . $row_end;
						}
						else if ( $options['show_height'] ) {
							$output .= $row_start . $options['height_label'] . $new_cell 
												  .  get_post_meta($post->ID, 'player_height', true ) . $row_end;
						}
						else if( $options['show_weight'] ) {
							$output .= $row_start . $options['weight_label'] . $new_cell 
												  .  get_post_meta($post->ID, 'player_weight', true ) . $row_end;
						}

						//YEAR
						if( $options['show_year'] ) {
							$output .= $row_start . $options['year_label'] . $new_cell 
												  . get_post_meta($post->ID, 'player_year', true ) . $row_end;
						}
						
						//AGE
						if( $options['show_age'] ) {
							$output .= $row_start . $options['age_label'] . $new_cell 
												  .  get_post_meta($post->ID, 'player_age', true ) . $row_end;
						}
						
						//EXPERIENCE
						if( $options['show_experience'] ) {
							$output .= $row_start . $options['experience_label'] . $new_cell 
												  . get_post_meta($post->ID, 'player_experience', true ) . $row_end;
						}
						
						//HOME TOWN
						if( $options['show_home_town'] ) {
							$output .= $row_start . $options['home_town_label'] . $new_cell 
												  . get_post_meta($post->ID, 'player_home_town', true ) . $row_end;
						}
						
						//LAST SCHOOL
						if( $options['show_last_school'] ) {
							$output .= $row_start . $options['last_school_label'] . $new_cell 
												  . get_post_meta($post->ID, 'player_last_school', true ) . $row_end;
						}
						
						//COUNTRY
						if( $options['show_country'] ) {
							$output .= $row_start . $options['country_label'] . $new_cell 
												  . get_post_meta($post->ID, 'player_country', true ) . $row_end;
						}
						
						//OTHER INFO
						if( $options['show_other_info'] ) {
							$output .= $row_start . $options['other_info_label'] . $new_cell 
												  . get_post_meta($post->ID, 'player_other', true ) . $row_end;
						}		
				 $output .= "</tbody>\n";
				$output .= "</table>\n";
				
				$output .= "</div> <!-- .player-info-container -->\n";
				
				$output .= "</div> <!-- .player-tile -->\n";
			} //end foreach( $posts as $post )
		} //end if( have_posts( ) )
		else {
			$output = sprintf( __( "%sNo players found on team: '%s'%s", 'team-rosters' ), '<h1>', $team_slug, '</h1>' );
		}

		return $output;
		
	} //End: mstw_tr_build_gallery()
 }

 //-----------------------------------------------------------
 //	8. mstw_tr_build_player_photo: constructs the html for the 
 //		player photo all front-end displays ...  
 //		the single player profiles, roster galleries, and tables
 //
 if ( !function_exists( 'mstw_tr_build_player_photo' ) ) {
	function mstw_tr_build_player_photo( $player, $team_slug, $options, $display = 'profile' ) {
		  
		//1. Use the player photo (thumbnail) if available
		//2. Else use the team logo from the teams DB, if available,
		//3. Else use the team logo in the theme's /team-rosters/images/ dir
		//3. Else use the default-photo-team-slug.png from the plugin images dir
		//4. Else use the default-photo.png (mystery player) from the plugin images dir
		
		//mstw_tr_log_msg( 'in mstw_tr_build_player_photo ... ' );
		//mstw_tr_log_msg( '$team_slug= ' );
		//mstw_tr_log_msg( $team_slug );
		
		
		// This is the default if nothing else can be found
		$photo_file_url = '';
		$photo_html = '';
		$logo_html = '';

		if ( has_post_thumbnail( $player->ID ) ) { 
			// Use the player's thumbnail (featured image) if available
			$photo_file_url = wp_get_attachment_thumb_url( get_post_thumbnail_id( $player->ID ) );
			$first_name = get_post_meta($player->ID, 'player_first_name', true );
			$last_name = get_post_meta($player->ID, 'player_last_name', true );
			$alt = "$first_name $last_name";
			$photo_html = "<img src='$photo_file_url' alt='$alt' />";
			
		} else {
			// Try to build a team logo
			$photo_html = mstw_tr_build_team_logo( $team_slug );
		
		}
		//mstw_tr_log_msg( '$photo_html = ' );
		//mstw_tr_log_msg( $photo_html );
		
		if( !$photo_html ) {
			// Give up and use the "mystery man"
			//$default_img_dir = plugin_dir_path( dirname( __FILE__ ) ) . 'images/default-images/';
			//mstw_tr_log_msg( '$default_img_dir = ' . $default_img_dir );
			//$default_img_url = plugins_url( ) . '/team-rosters/images/default-images/';
			$photo_file_url = plugins_url( ) . '/team-rosters/images/default-images/default-photo.png';
			$alt = __( 'No player photo found.', 'team-rosters' );
			$photo_html = "<img src='$photo_file_url' alt='$alt' />";
		}
			
		//
		// add the link to the player profile, if appropriate
		//
		if ( $display != 'profile' ) {
			if ( isset( $options['links_to_profiles'] ) and $options['links_to_profiles'] ) {
				$photo_html = '<a href="' .  get_permalink( $player->ID ) . '">' . $photo_html . '</a>';
			}
		}
			
		return $photo_html;
			
	} //End: mstw_tr_build_player_photo()
 }
 
 //-----------------------------------------------------------
 //	9. mstw_tr_build_player_name: constructs a player name based
 //		on first, last, and $options['name_format']
 //		Link to player profile is based on $display, profiles don't have links to themselves,
 //		and $options['links_to_profiles']
 //
 if ( !function_exists( 'mstw_tr_build_player_name' ) ) {
	function mstw_tr_build_player_name( $player, $options, $display = 'profile' ) {
		
		$first_name = get_post_meta($player->ID, 'player_first_name', true );
		$last_name = get_post_meta($player->ID, 'player_last_name', true );
		
		switch ( $options['name_format'] ) { 
			case 'first-last':
				$player_name = ( $display == 'profile' ) ? $first_name . '<br/>' . $last_name : "$first_name $last_name";
				break; 
			case 'first-only':
				$player_name = $first_name;
				break;
			case 'last-only':
				$player_name = $first_name;
				break;
			case 'last-first':
			default:
				$player_name = ( $display == 'profile' ) ? $last_name . ',<br/>' . $first_name : "$last_name, $first_name";
				break; 
		} 
		
		$player_html = $player_name;
		
		if( $display != 'profile' ) {
			if ( $options['links_to_profiles'] ) {
				$player_html = '<a href="' .  get_permalink( $player->ID ) . '?roster_type=' . $options['roster_type'] . '" ';
				$player_html .= '>' . $player_name . '</a>';
			}
		}
		
		return $player_html;
		
	} //End: mstw_tr_build_player_name()
 }
 
 //-----------------------------------------------------------
 //	10. mstw_tr_build_profile_logo - constructs the html for the 
 //		player photo all front-end displays ...  
 //		the single player profiles, roster galleries, and tables
 //
 if ( !function_exists( 'mstw_tr_build_profile_logo' ) ) {
	function mstw_tr_build_profile_logo( $team_slug ) {
		//mstw_tr_log_msg( 'in mstw_tr_build_profile_logo ...' );
		
		//this is the default return
		$logo_html = '';
		
		//mstw_tr_log_msg( 'calling mstw_tr_build_team_logo( $team_slug )' );
		//mstw_tr_log_msg( $team_slug );
		
		$logo_html = mstw_tr_build_team_logo( $team_slug );
		//mstw_tr_log_msg( '$logo_html: ' );
		//mstw_tr_log_msg( $logo_html );
		
		return $logo_html;
		
	} //End: mstw_tr_build_profile_logo( )	 
 }
 
 
 //-----------------------------------------------------------
 //	11. mstw_tr_build_bats_throws - constructs the html for the 
 //		bats-throws field in all displays ...  
 //		the single player profiles, roster galleries, and tables
 //
 if( !function_exists( 'mstw_tr_build_bats_throws' ) ) {
	function mstw_tr_build_bats_throws( $player ) {
	
		//return variable
		$html = ''; 
		
		$bats_throws = array( get_post_meta( $player->ID, 'player_bats', true ),
							  get_post_meta( $player->ID, 'player_throws', true ),
							);
	
		for ( $i = 0; $i < sizeof( $bats_throws ); $i++ ) {
			if ( 1 == $i ) {
				$html .= '/';
			}
			//mstw_tr_log_msg( "in mstw_tr_build_bats_throws ... " . $bats_throws[ $i ] );
			
			switch ( $bats_throws[ $i ] ) {
				
				case 1:
				case __( 'R', 'team-rosters' ):
					$html .= __( 'R', 'team-rosters' );
					break;
				case 2:
				case __( 'L', 'team-rosters' ):
					$html .= __( 'L', 'team-rosters' );
					break;
				case 3:
				case __( 'B', 'team-rosters' ):
					$html .= __( 'B', 'team-rosters' );
					break;
				case 0:
				case '':
				default: 
					// no value specified, so do nothing
					break;
			}
		}
		
		return $html;
		
	} //End: mstw_tr_build_bats_throws( ) 
 }
 
 //-----------------------------------------------------------
 //	12. mstw_tr_is_valid_roster_type - checks if $roster_type  
 //			is valid. Returns true or false
 //
 if( !function_exists( 'mstw_tr_is_valid_roster_type' ) ) {
	function mstw_tr_is_valid_roster_type( $roster_type ) {
		$valid_types = array( 'high-school',
							  'baseball-high-school',
							  'college',
							  'baseball-college',
							  'pro',
							  'baseball-pro',
							  'custom',
							  );
		
		return in_array( $roster_type, $valid_types );
 
	 } //End: mstw_tr_is_valid_roster_type( )
 }
 
//----------------------------------------------------------------
// 13. mstw_tr_admin_notice - Displays all admin notices; 
//		callback for admin_notices action hook
//	ARGUMENTS: 	
//		None.
//	RETURNS:	
//		None. Displays all messages in the $transient transient
//			(then deletes it)
//
if ( !function_exists ( 'mstw_tr_admin_notice' ) ) {
	function mstw_tr_admin_notice( ) {
		//mstw_tr_log_msg( "mstw_tr_admin_notice:" );
		
		$transient = 'mstw_tr_admin_notices';
		
		if ( get_transient( $transient ) !== false ) {
			// get the types and messages
			$messages = get_transient( $transient );
			// display the messages
			foreach ( $messages as $message ) {
				$msg_type = $message['type'];
				$msg_notice = $message['notice'];
				
				// Kludge to get warning messages to appear after page title
				$msg_type = ( $msg_type == 'warning' ) ? 'updated ' . $msg_type : $msg_type ;
			?>
				<div class="<?php echo $msg_type; ?>">
					<p><?php echo $msg_notice; ?></p>
				</div>
			
			<?php
			}

			delete_transient( $transient );
			
		} //End: if ( get_transient( $transient ) )
	} //End: function mstw_tr_admin_notice( )
}

//----------------------------------------------------------------
// 13.1. mstw_tr_add_admin_notice - Adds admin notices to transient 
//		for display on admin_notices hook
//
//	ARGUMENTS: 	$type - type of notice [updated|error|update-nag|warning]
//				$notice - notice text
//
//	RETURNS:	None. Stores notice and type in transient for later display on admin_notices hook
//
if ( !function_exists ( 'mstw_tr_add_admin_notice' ) ) {
	function mstw_tr_add_admin_notice( $type = 'updated', $notice ) {
		//mstw_tr_log_msg( "mstw_tr_add_admin_notice:" );
		
		$transient = 'mstw_tr_admin_notices';
		
		//default type to 'updated'
		if ( !( $type == 'updated' or $type == 'error' or $type =='update-nag' or $type == 'warning' ) ) {
			$type = 'updated';
		}
		
		//set the admin message
		$new_msg = array( array(
							'type'	=> $type,
							'notice'	=> $notice
							)
						);

		//either create or add to the sss_admin transient
		$existing_msgs = get_transient( $transient );
		
		if ( $existing_msgs === false ) {
			// no transient exists, create it with the current message
			set_transient( $transient, $new_msg, HOUR_IN_SECONDS );
		} 
		else {
			// transient exists, append current message to it
			$new_msgs = array_merge( $existing_msgs, $new_msg );
			set_transient ( $transient, $new_msgs, HOUR_IN_SECONDS );
		}
		
		$new_transient = get_transient( $transient );
		//mstw_tr_log_msg( "New mstw_tr_admin_notices transient:" );
		//mstw_tr_log_msg( $new_transient );
		
	} //End: function mstw_tr_add_admin_notice( )
}
 
 //-----------------------------------------------------------
 //	14. mstw_tr_build_team_colors_html: builds the HTML for team colors
 //			when the 'use team colors' display option is set. Returns
 //			non-empty HTML if the $team is linked to a team in the 
 //			the Schedules & Scoreboards DB.
 //	
 if ( !function_exists( 'mstw_tr_build_team_colors_html' ) ) {
	function mstw_tr_build_team_colors_html( $team = null, $options = null, $type = 'table' ) {
		//mstw_tr_log_msg( 'in mstw_tr_build_team_colors_html ...' );
		//mstw_tr_log_msg( 'mstw_ss_team post type exists: ' . post_type_exists( 'mstw_ss_team' ) );
		//mstw_tr_log_msg( '$team = ' . $team );
		
		$html = ''; // default return string
		
		// return if $team is not specified or mstw_ss_team doesn't exist
		if( $team && post_type_exists( 'mstw_ss_team' ) ) {
			
			//Check that $team is linked to a team in the S&S or LM DB
			if( $team_obj = mstw_tr_find_team_in_plugin( $team ) ) {
				//mstw_tr_log_msg( 'found $team_obj ...' );
				//mstw_tr_log_msg( 'ID= ' . $team_obj->ID );
				
				// check that 'use_team_colors' is set for tables or
				//	'sp_use_team_colors' is set for profiles & galleries
				if ( isset( $options ) ) {
					if ( 'table' == $type ) {
						if( array_key_exists( 'use_team_colors', $options ) && $options['use_team_colors'] ) {
						  $html .= mstw_tr_build_hidden_fields( $team, $team_obj );
						}
					} 
					else {
						if( array_key_exists( 'sp_use_team_colors', $options ) && $options['sp_use_team_colors'] ) {
						   $html .= mstw_tr_build_hidden_fields( $team, $team_obj );
						}
					}

				} //End: if ( isset( $options ) )
				
			} //End: if( $team_obj = mstw_tr_find_team_in_plugin( $team ) )
				
		} //End: if( $team && post_type_exists( 'mstw_ss_team' ) )
				
		return $html;
		
	} //End: mstw_tr_build_team_colors_html()
 }

 //-----------------------------------------------------------
 //	15. mstw_tr_build_hidden_fields: builds the hidden fields for the 
 //			JavaScript to use when using the teams DB colors. Called 
 //			by mstw_tr_build_team_colors_html()
 //	
 if ( !function_exists( 'mstw_tr_build_hidden_fields' ) ) {
	function mstw_tr_build_hidden_fields( $team, $team_obj ) {
		//mstw_tr_log_msg( 'in mstw_tr_build_hidden_fields ...' ); 
					
		// jQuery looks first for this element
		$html = "<mstw-team-colors class='$team' id='$team' style='display: none'>\n";
		
		$bkgd_color = get_post_meta( $team_obj->ID, 'team_primary_bkgd_color', true );
		if( $bkgd_color ) {
			$html .= "<team-color id='bkgd-color' >$bkgd_color</team-color>\n";
		}
		
		$text_color = get_post_meta( $team_obj->ID, 'team_primary_text_color', true );
		if( $text_color ) {
			$html .= "<team-color id='text-color' >$text_color</team-color>\n";
		}
		
		$accent_1 = get_post_meta( $team_obj->ID, 'team_accent_color_1', true );
		if( $accent_1 ) {
			$html .= "<team-color id='accent-1' >$accent_1</team-color>\n";
		}
		
		$accent_2 = get_post_meta( $team_obj->ID, 'team_accent_color_2', true );
		if( $accent_2 ) {
			$html .= "<team-color id='accent-2' >$accent_2</team-color>\n";
		}
		
		$html .= "</mstw-team-colors>\n";
				
		return $html;
		
	} //End: mstw_tr_build_hidden_fields()
 }

 //-----------------------------------------------------------
 //	16. mstw_tr_find_team_in_plugin: Determines if the $team is linked 
 //		to a team in the S&S or LM plugin DB. 
 //		ARGUMENTS:
 //			$team = TEAM ROSTERS team slug
 //		RETURNS:
 //			null if team is not linked
 //			team object FROM S&S or LM if linked
 //	
 if ( !function_exists( 'mstw_tr_find_team_in_plugin' ) ) {
	function mstw_tr_find_team_in_plugin( $team_slug = null, $ss_only = false ) {
		//mstw_tr_log_msg( 'mstw_tr_find_team_in_plugin:' );
		//mstw_tr_log_msg( '$team = ' . $team_slug );
		
		$retval = null;
		
		if ( null === $team_slug ) {
			return $retval;
		}
		
		$team_obj = get_term_by( 'slug', $team_slug, 'mstw_tr_team', OBJECT, 'raw' );
		
		if ( false !== $team_obj ) {
			
			$source = get_term_meta( $team_obj -> term_id, 'tr_link_source', true );
			
			if ( 'mstw_ss_team' == $source || ( !$ss_only && 'mstw_lm_team' == $source ) ) {
				$link_team_slug = get_term_meta( $team_obj -> term_id, 'tr_team_link', true );
				// returns null on failure so that's the default
				$retval = get_page_by_path( $link_team_slug, OBJECT, $source );
					
			} else {
				$retval = null;
				
			}
			
		}
		
		return $retval;
		
	} //End: mstw_tr_find_team_in_plugin( )
 }
 
 //-----------------------------------------------------------
 //	17. mstw_tr_build_team_logo: Builds the HTML for a team logo 
 //		ARGUMENTS:
 //			$team_slug - $slug for the team IN THE TR DB
 //		RETURNS:
 //			null if logo can't be found/built
 //			logo html with alt, and with link to team site, if available
 //	
 if ( !function_exists( 'mstw_tr_build_team_logo' ) ) {
	function mstw_tr_build_team_logo( $team_slug = null, $type='player' ) {
		//1. Use the team logo from the S&S or LM DB, if available,
		//2. Else use the team logo in the theme's /team-rosters/images/ dir
		//3. Else use the default-logo-team-slug.png from the plugin images dir
		//4. Else use the default-logo.png (mystery player) from the plugin images dir

		//mstw_tr_log_msg( 'in  mstw_tr_build_team_logo ... ' );
		//mstw_tr_log_msg( '$team = ' );
		//mstw_tr_log_msg( $team_slug );
		
		if( null === $team_slug ) {
			return null; 
		}
		
		// These are the defaults if nothing else can be found
		$logo_html = '';
		$alt = '';
		
		//mstw_tr_log_msg( "team slug: $team_slug" );
		if( $team_obj = mstw_tr_find_team_in_plugin( $team_slug ) ) { 
			// Look for team logo in Schedules & Scoreboards team DB
			$team_logo = get_post_meta( $team_obj->ID, 'team_alt_logo', true );
			//mstw_tr_log_msg( "team logo: $team_logo" );
			
			if( $team_logo ) {
				$logo_url = $team_logo;
				$alt = get_the_title( $team_obj ) ;
				$logo_html = "<img src='$team_logo' alt='$alt' />";
				$team_site_link = get_post_meta( $team_obj->ID, 'team_link', true );
				if ( $team_site_link ) {
					$logo_html = "<a href=$team_site_link target='_blank'> $logo_html </a>";
				}
			}
		}
		
		if ( empty( $logo_html ) ) {
			// Struck out on the S&S and LM DB, so look for plugin's custom images
			$theme_image = get_stylesheet_directory( ) . '/team-rosters/images/default-logo-' . $team_slug . '.png';
			//mstw_tr_log_msg( '$theme_image = ' . $theme_image );
			
			// First in the theme/team-rosters/images directory
			if ( file_exists( $theme_image ) ) {
				// First look in /team-rosters/images/ directory in the 
				// theme (or child theme) main directory
				$logo_html = dirname( get_stylesheet_uri( ) ) . "/team-rosters/images/default-logo-$team_slug.png";
				//mstw_tr_log_msg( '$logo_html = ' . $logo_html );
					
			} else {
				// Then in the plugin's /images/default-images/ directory
				$default_img_file = plugin_dir_path( dirname( __FILE__ ) ) . "images/default-images/default-logo-$team_slug.png";
				//mstw_tr_log_msg( '$default_img_dir = ' . $default_img_dir );
				 
				if ( file_exists( $default_img_file ) ) {
					// Then look in the plugin's /images/default-images/ directory
					$logo_html = plugins_url( ) . "/team-rosters/images/default-images/default-logo-$team_slug.png";	
				}
			}
			
			if( !empty( $logo_html ) ) {
				// If an image is found, try to add an alt
				$term = get_term_by( 'slug', $team_slug, 'mstw_tr_team' );
				if( $term ) {
					// Lets alt disappear if there's no term
					$alt = 'alt="'. $term->name . '"';
				}
				$logo_html = "<img src='$logo_html' $alt />";
			}
		} 
		
		return $logo_html;
		
	} //End: mstw_tr_build_team_logo( )
 }
 
//-------------------------------------------------------------------------
// 20. mstw_tr_get_current_team - gets the current team/roster from the options DB 
//
//	ARGUMENTS: 
//		None
//
//	RETURNS:
//		The current team/roster (slug). If no current team is set, it will return 
//		the first team in the DB, or '' there are no teams in the DB
//
if ( !function_exists( 'mstw_tr_get_current_team' ) ) {
	function mstw_tr_get_current_team( ) {
		//mstw_tr_log_msg( "mstw_tr_get_current_team" );
		
		//for testing only
		//$this -> set_current_team( '' );
		
		$current_team = get_option( 'tr-current-team', '' );
		
		// We should get a current team, but in case we don't we'll take the
		// first one find and set it as current
		
		if ( '' == $current_team or -1 == $current_team ) {
			// This should only happen the first time the plugin is run
			//mstw_tr_log_msg( "current team not found" );
			
			$args = array(
				'taxonomy'   => 'mstw_tr_team', 
				'hide_empty' => false,
				'orderby'    => 'name',
				);
				
			$teams = get_terms( $args );
			
			if ( $teams ) {
				$current_team = $teams[0]->slug;
				mstw_tr_set_current_team( $current_team );
			}
		}
		
		return $current_team;
		
	} //End: mstw_tr_get_current_team( )
}

//-------------------------------------------------------------------------
// 21. mstw_tr_set_current_team - sets the current team (slug) in the options DB 
//
//	ARGUMENTS: 
//		$team_slug - the current team slug to be set
//
//	RETURNS:
//		True of current team is updated, false if update fails
//
if ( !function_exists( 'mstw_tr_set_current_team' ) ) {
	function mstw_tr_set_current_team( $team_slug = '' ) {
		//mstw_tr_log_msg( "mstw_tr_set_current_team: setting to $team_slug" );	
		
		return update_option( 'tr-current-team', $team_slug );
			
	} //End: mstw_tr_set_current_team( )
}

//--------------------------------------------------------------------------------------
// 12. mstw_tr_help_sidebar - sets the WP help sidebar for a screen
//
//	ARGUMENTS: 
//		$screen - WP screen object for which to set the help sidebar
//
//	RETURNS:
//		Builds sidebar HTML and sets it for $screen
//
if ( !function_exists( 'mstw_tr_help_sidebar' ) ) {
	function mstw_tr_help_sidebar( $screen ) {
		//mstw_tr_log_msg( "mstw_tr_help_sidebar:" );
		
		$sidebar = "<p><strong>" . __( 'For more information:', 'team-rosters' ) . '</strong></p>
		
		<p><a href="http://shoalsummitsolutions.com/category/users-manuals/tr-plugin/" target="_blank">' . __( 'MSTW Team Rosters Users Manual', 'team-rosters' ) . '</a></p>
		
		<p><a href="http://dev.shoalsummitsolutions.com/team-rosters/" target="_blank">' . __( 'See MSTW Team Rosters in Action', 'team-rosters' ) . '</a></p>
		
		<p><a href="http://wordpress.org/plugins/team-rosters/" target="_blank">' . __( 'MSTW Team Rosters on WordPress.org', 'team-rosters' ) . '</a></p>
		
		<p><a href="http://shoalsummitsolutions.com/support-options/#gold-support" target="_blank">' . __( 'Need more help? Want to contribute? Register for MSTW Gold Support', 'team-rosters' ) . '</a></p>';
		
		$screen->set_help_sidebar( $sidebar );
		
	} //End: mstw_tr_help_sidebar( )
}

/*-----------------------------------------------------------------------------
 *
 * MOVED FROM MSTW_UTILITY_FUNCTIONS.PHP TO REMOVE DEPENDENCY ON IT
 *
 *----------------------------------------------------------------------------*/

//-------------------------------------------------------------------------------
// 40. mstw_tr_build_admin_edit_screen - Convenience function to build admin UI 
//									 data entry screens
//	ARGUMENTS: $fields = array(
//		'type'       => $type,
//		'id'         => $id,
//		'desc'       => $desc,
//		'curr_value' => current field value,
//		'options'    => array of options in key=>value pairs
//			e.g., array( __( '08:00 (24hr)', 'mstw-loc-domain' ) => 'H:i', ... )
//		'label_for'  => $id,
//		'class'      => $class,
//		'name'		 => $name,
//	);
//
if( !function_exists( 'mstw_tr_build_admin_edit_screen' ) ) {	
	function mstw_tr_build_admin_edit_screen( $fields ) {
		
		foreach( $fields as $field_id=>$field_data ) {
			//HANDLE table dividers here ... NEW
			if ( $field_data['type'] == 'divider' ) {
				$divider_msg = ( isset( $field_data['curr_value'] ) ) ? $field_data['curr_value'] : '&nbsp;&nbsp;';
				?>
				<tr class='mstw-divider-spacer'><td>&nbsp;&nbsp;</td></tr>
				<tr class='mstw-divider'><th colspan=2 ><?php echo $divider_msg ?></th></tr>
				<?php
			}
			else {
				$field_data['id'] = ( !isset( $field_data['id'] ) || empty( $field_data['id'] ) ) ? $field_id : $field_data['id'];
				$field_data['name'] = ( !isset( $field_data['name'] ) || empty( $field_data['name'] ) ) ? $field_id : $field_data['name'];
				
				// check the field label/title
				if ( array_key_exists( 'label', $field_data ) && !empty( $field_data['label'] ) )
					$label = $field_data['label'];
				else
					$label = '';
				?>
				
				<tr>
					<th><label for '<?php echo $field_data['id']?>' >
						<?php echo $label ?>
					</label></th>
					<?php 
					// media-uploader will add it's own cells (3 of theme)
					if ( $field_data['type'] != 'media-uploader' ) { 
						echo "<td>\n";
					}

						
						mstw_tr_build_admin_edit_field( $field_data );

					if ( $field_data['type'] != 'media-uploader' ) { 
						echo "</td>\n";
					}
					?>
					</tr>
				<?php
			}
		}
		
	} //End: mstw_tr_build_admin_edit_screen()
}

//-------------------------------------------------------------------------------
// 41. mstw_tr_build_admin_edit_field - Helper function for building HTML for all admin 
//								form fields ... ECHOES OUTPUT
//
//	ARGUMENTS: $args = array(
//		'type'       => $type,
//		'id'         => $id,
//		'desc'       => $desc,
//		'curr_value' => current field value,
//		'options'    => array of options in key=>value pairs
//			e.g., array( __( '08:00 (24hr)', 'mstw-loc-domain' ) => 'H:i', ... )
//		'label_for'  => $id,
//		'class'      => $class,
//		'name'		 => $name,
//	);
//		
//
if( !function_exists( 'mstw_tr_build_admin_edit_field' ) ) {
	function mstw_tr_build_admin_edit_field( $args ) {
	
		$defaults = array(
				'type'		 => 'text',
				'id'      	 => 'default_field', // the ID of the setting in our options array, and the ID of the HTML form element
				'title'   	 => __( 'Default Field', 'mstw-loc-domain' ), // the label for the HTML form element
				'label'   	 => __( 'Default Label', 'mstw-loc-domain' ), // the label for the HTML form element
				'desc'   	 => '', // the description displayed under the HTML form element
				'default'	 => '',  // the default value for this setting
				'type'    	 => 'text', // the HTML form element to use
				'options' 	 => array(), // (optional): the values in radio buttons or a drop-down menu
				'name' 		 => '', //name of HTML form element. should be options_array[option]
				'class'   	 => '',  // the HTML form element class. Also used for validation purposes!
				'curr_value' => '',  // the current value of the setting
				'maxlength'	 => '',  // maxlength attrib of some input controls
				'size'	 	 => '',  // size attrib of some input controls
				'img_width'  => 60,
				'btn_label'  => 'Upload from Media Library',
				);
		
		// "extract" to be able to use the array keys as variables in our function output below
		$args = wp_parse_args( $args, $defaults );
	
		extract( $args );
		
		// default name to id
		$name = ( !empty( $name ) ) ? $name : $id;
		
		// pass the standard value if the option is not yet set in the database
		//if ( !isset( $options[$id] ) && $options[ != 'checkbox' && ) {
		//	$options[$id] = ( isset( $default ) ? $default : 'default_field' );
		//}
		
		// Additional field class. Output only if the class is defined in the $args()
		$class_str = ( !empty( $class ) ) ? "class='$class'" : '' ;
		$maxlength_str = ( !empty( $maxlength ) ) ? "maxlength='$maxlength'" : '' ;
		$size_str = ( !empty( $size ) ) ? "size='$size'" : '' ;
		$attrib_str = " $class_str $maxlength_str $size_str ";

		// switch html display based on the setting type.
		switch ( $args['type'] ) {
			//TEXT & COLOR CONTROLS
			case 'text':	// this is the default type
			case 'color':  	// color field is just a text field with associated JavaScript
			?>
				<input type="text" id="<?php echo $id ?>" name="<?php echo $name ?>" value="<?php echo $curr_value ?>" <?php echo $attrib_str ?> />
			<?php
				echo ( !empty( $desc ) ) ? "<br /><span class='description'>$desc</span>\n" : "";
				break;
				
			//SELECT OPTION CONTROL
			case 'select-option':
				//not sure why this is needed given the extract() above
				//but without it you get an extra option with the 
				//'option-name' displayed (huh??)
				$options = $args['options'];
					
				echo "<select id='$id' name='$name' $attrib_str >";
					foreach( $options as $key=>$value ) {
						$selected = ( $curr_value == $value ) ? 'selected="selected"' : '';
						echo "<option value='$value' $selected>$key</option>";
					}
				echo "</select>";
				echo ( !empty( $desc ) ) ? "<br /><span class='description'>$desc</span>" : "";
				break;
			
			// CHECKBOX
			case 'checkbox':
				echo "<input class='checkbox $class_str' type='checkbox' id='$id' name='$name' value=1 " . checked( $curr_value, 1, false ) . " />";
				echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";	
				break;
				
			// LABEL
			case 'label':
				echo "<span class='description'>" . $curr_value . "</span>";
				echo ( '' != $desc ) ? "<br /><span class='description'>$desc</span>" : "";
				break;
				
			// MEDIA UPLOADER
			case 'media-uploader':
				?>
				<td class="uploader">
					<input type="text" name="<?php echo $id  ?>" id="<?php echo $id ?>" class="mstw_logo_text" size="30" value="<?php echo $curr_value ?>"/>
					<?php echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : ""; ?>
				</td>
				
				<td class="uploader">
				  <input type="button" class="button" name="<?php echo $id . '_btn'?>" id="<?php echo $id . '_btn'?>" value="<?php echo $btn_label ?>" />
				<!-- </div> -->
				</td>
				<td>
				<img id="<?php echo $id . '_img' ?>" width="<?php echo $img_width ?>" src="<?php echo $curr_value ?>" />
				</td>
		<?php
				break;
				

			//---------------------------------------------------------------
			// THE FOLLOWING CASES HAVE NOT BEEN TESTED/USED
			
			case "multi-text":
				foreach($options as $item) {
					$item = explode("|",$item); // cat_name|cat_slug
					$item[0] = esc_html__($item[0], 'wptuts_textdomain');
					if (!empty($options[$id])) {
						foreach ($options[$id] as $option_key => $option_val){
							if ($item[1] == $option_key) {
								$value = $option_val;
							}
						}
					} else {
						$value = '';
					}
					echo "<span>$item[0]:</span> <input class='$field_class' type='text' id='$id|$item[1]' name='" . $wptuts_option_name . "[$id|$item[1]]' value='$value' /><br/>";
				}
				echo ($desc != '') ? "<span class='description'>$desc</span>" : "";
			break;
			
			case 'textarea':
				$options[$id] = stripslashes($options[$id]);
				$options[$id] = esc_html( $options[$id]);
				echo "<textarea class='textarea$field_class' type='text' id='$id' name='" . $wptuts_option_name . "[$id]' rows='5' cols='30'>$options[$id]</textarea>";
				echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : ""; 		
			break;

			case 'select2':
				echo "<select id='$id' class='select$field_class' name='" . $wptuts_option_name . "[$id]'>";
				foreach($options as $item) {
					
					$item = explode("|",$item);
					$item[0] = esc_html($item[0], 'wptuts_textdomain');
					
					$selected = ($options[$id]==$item[1]) ? 'selected="selected"' : '';
					echo "<option value='$item[1]' $selected>$item[0]</option>";
				}
				echo "</select>";
				echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";
			break;

			case "multi-checkbox":
				foreach($options as $item) {
					
					$item = explode("|",$item);
					$item[0] = esc_html($item[0], 'wptuts_textdomain');
					
					$checked = '';
					
					if ( isset($options[$id][$item[1]]) ) {
						if ( $options[$id][$item[1]] == 'true') {
							$checked = 'checked="checked"';
						}
					}
					
					echo "<input class='checkbox$field_class' type='checkbox' id='$id|$item[1]' name='" . $wptuts_option_name . "[$id|$item[1]]' value='1' $checked /> $item[0] <br/>";
				}
				echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";
			break;
			
			default:
				mstw_tr_log_msg( "CONTROL TYPE $type NOT RECOGNIZED." );
				echo "CONTROL TYPE $type NOT RECOGNIZED.";
			break;
			
		}	
	} //End: mstw_tr_build_admin_edit_field()
}

//-------------------------------------------------------------------------------
// 42. mstw_tr_build_settings_screen - builds admin settings form (using settings api)
//		ARGUMENTS:
//		  $arguments: array of argument arrays passed to mstw_tr_build_settings_field()
//		RETURN:
//			None. HTML is ouput/echoed to the screen by mstw_tr_build_settings_field()
//
if( !function_exists( 'mstw_tr_build_settings_screen' ) ) {	
	function mstw_tr_build_settings_screen( $arguments ) {
		foreach ( $arguments as $args ) {
			mstw_tr_build_settings_field( $args );
		}
	}  //End: mstw_tr_build_settings_screen()
}

//-------------------------------------------------------------------------------
// 43. mstw_tr_build_settings_field - builds admin screen form fields
//		ARGUMENTS:
//		  $args: array of arguments used to build form field (see descriptions below
//		  $is_setting: true|false 
//		  true: use settings API to create a settings field (add_settings_field()) //		 false: just build the html form/input field; don't create setting
//		RETURN:
//			None. HTML is output/echoed to screen
//		
if( !function_exists( 'mstw_tr_build_settings_field' ) ) {	
	function mstw_tr_build_settings_field( $args ) {
		// default array to overwrite when calling the function
		
		$defaults = array(
			'id'      => 'default_field', // the ID of the setting in our options array, and the ID of the HTML form element
			'title'   => 'Default Field',  // the label for the HTML form element
			'desc'    => '', // the description displayed under the HTML form element
			'default'     => '',  // the default value for this setting
			'type'    => 'text', // the HTML form element to use
			'section' => '', // settings section to which this setting belongs
			'page' => '', //page on which the section belongs
			'options' => array(), // (optional): the values in radio buttons or a drop-down menu
			'name' => '', //name of HTML form element. should be options_array[option]
			'class'   => '',  // the HTML form element class. Also used for validation purposes!
			'value' => ''  // the current value of the setting
		);
		
		//	ARGUMENTS: $field_args = array(
		//		'type'       => $type, 	*
		//		'id'         => $id,	*
		//		'desc'       => $desc,	*
		//		'curr_value' => $value,	*
		//		'options'    => $options,	*
		//		'label_for'  => $id,	* (use id)
		//		'class'      => $class, *
		//		'name'		 => $name,
		//	);
		
		// "extract" to be able to use the array keys as variables in our function output below
		extract( wp_parse_args( $args, $defaults ) );
		
		//Handle some MSTW custom field types; convert for generic select-option
		switch ( $type ) {
			case 'show-hide':
				$type = 'select-option';
				$options = array(	__( 'Show', 'mstw-loc-domain' ) => 1, 
									__( 'Hide', 'mstw-loc-domain' ) => 0, 
								  );
				break;
			case 'date-time':
				$type = 'select-option';
				
				$options = array ( 	__( 'Custom', 'mstw-loc-domain' ) => 'custom',
									__( 'Tuesday, 07 April 01:15 pm', 'mstw-loc-domain' ) => 'l, d M h:i a',
									__( 'Tuesday, 7 April 01:15 pm', 'mstw-loc-domain' ) => 'l, j M h:i a',
									__( 'Tuesday, 07 April 1:15 pm', 'mstw-loc-domain' ) => 'l, d M g:i a',
									__( 'Tuesday, 7 April 1:15 pm', 'mstw-loc-domain' ) => 'l, j M g:i a',
									__( 'Tuesday, 7 April 13:15', 'mstw-loc-domain' ) => 'l, d M H:i',
									__( 'Tuesday, 7 April 13:15', 'mstw-loc-domain' ) => 'l, j M H:i',
									__( '07 April 13:15', 'mstw-loc-domain' ) => 'd M H:i',
									__( '7 April 13:15', 'mstw-loc-domain' ) => 'j M H:i',
									__( '07 April 01:15 pm', 'mstw-loc-domain' ) => 'd M g:i a',
									__( '7 April 01:15 pm', 'mstw-loc-domain' ) => 'j M g:i a',		
									);
				
				if ( isset( $custom_format ) && $custom_format == 0 ) {
					//remove the custom option
					unset( $options[ __( 'Custom', 'mstw_loc_domain' ) ] );
				}
				
				if ( $desc == '' ) {
					$desc = __( 'Formats for 7 April 2013 13:15.', 'mstw-loc-domain' );
				}
				
				break;
			case 'date-only':
				$type = 'select-option';
				$options = array ( 	__( 'Custom', 'mstw-loc-domain' ) => 'custom',
									'2013-04-07' => 'Y-m-d',
									'13-04-07' => 'y-m-d',
									'04/07/13' => 'm/d/y',
									'4/7/13' => 'n/j/y',
									__( '07 Apr 2013', 'mstw-loc-domain' ) => 'd M Y',
									__( '7 Apr 2013', 'mstw-loc-domain' ) => 'j M Y',
									__( 'Tues, 07 Apr 2013', 'mstw-loc-domain' ) => 'D, d M Y',
									__( 'Tues, 7 Apr 13', 'mstw-loc-domain' ) => 'D, j M y',
									__( 'Tuesday, 7 Apr', 'mstw-loc-domain' ) => 'l, j M',
									__( 'Tuesday, 07 April 2013', 'mstw-loc-domain' ) => 'l, d F Y',
									__( 'Tuesday, 7 April 2013', 'mstw-loc-domain' ) => 'l, j F Y',
									__( 'Tues, 07 Apr', 'mstw-loc-domain' ) => 'D, d M',
									__( 'Tues, 7 Apr', 'mstw-loc-domain' ) => 'D, j M',
									__( '07 Apr', 'mstw-loc-domain' ) => 'd M',
									__( '7 Apr', 'mstw-loc-domain' ) => 'j M',
									);
									
				if ( isset( $custom_format ) && $custom_format == 0 ) {
					//remove the custom option
					unset( $options[ __( 'Custom', 'mstw_loc_domain' ) ] );
				}
				if ( $desc == '' ) {
					$desc = __( 'Formats for 7 Apr 2013. Default: 2013-04-07', 'mstw-loc-domain' );
				}
				break;
			case 'time-only':
				$type = 'select-option';
				$options = array ( 	__( 'Custom', 'mstw-loc-domain' ) 	=> 'custom',
									__( '08:00 (24hr)', 'mstw-loc-domain' ) => 'H:i',
									__( '8:00 (24hr)', 'mstw-loc-domain' ) 	=> 'G:i',
									__( '08:00 am', 'mstw-loc-domain' ) 	=> 'h:i a',
									__( '08:00 AM', 'mstw-loc-domain' ) 	=> 'h:i A',
									__( '8:00 am', 'mstw-loc-domain' ) 		=> 'g:i a',
									__( '8:00 AM', 'mstw-loc-domain' ) 		=> 'g:i A',
									);
									
				if ( isset( $custom_format ) && $custom_format == 0 ) {
					//remove the custom option
					unset( $options[ __( 'Custom', 'mstw_loc_domain' ) ] );
				}
				if ( $desc == '' ) {
					$desc = __( 'Formats for eight in the morning. Default: 08:00', 'mstw-loc-domain' );
				}
				break;
			default:
				break;
								
		}
		
		//
		// map arguments used by mstw_display_form_field() to create HTML output 
		//
		$field_args = array(
			'type'       => $type,
			'id'         => $id,
			'desc'       => $desc,
			'curr_value' => $value,
			'options'    => $options,
			'label_for'  => $id,
			'class'      => $class,
			'name'		 => $name,
		);
		
		add_settings_field( $id, 
			$title, 
			'mstw_tr_build_admin_edit_field', 
			$page, 
			$section, 
			$field_args 
			);
		
	} //End: mstw_tr_build_settings_field()
}