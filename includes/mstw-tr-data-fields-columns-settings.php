<?php
/*----------------------------------------------------------------------------
 * mstw-tr-data-fields-columns-settings.php
 *	All functions for the MSTW Team Rosters Plugin's data fields & columns settings.
 *	Loaded by mstw-tr-settings.php 
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
 *--------------------------------------------------------------------------*/

//-----------------------------------------------------------------	
// 	This first function is here for potential future page re-org	
//		
	if( !function_exists( 'mstw_tr_data_fields_columns_setup' ) ) {
		function mstw_tr_data_fields_columns_setup( ) {
			//mstw_tr_log_msg( 'mstw_tr_data_fields_columns_setup:' );
			
			mstw_tr_data_fields_section_setup( );
		}
	} //End: mstw_tr_data_fields_columns_setup()
	
	if( !function_exists( 'mstw_tr_data_fields_section_setup' ) ) {
		function mstw_tr_data_fields_section_setup( ) {
			//mstw_tr_log_msg( 'mstw_tr_data_fields_section_setup:' );	
			
			// Roster Table data fields/columns -- show/hide and labels
			$display_on_page = 'mstw-tr-data-fields-columns';
			$page_section = 'mstw_tr_fields_columns_settings';
			
			$options = wp_parse_args( get_option( 'mstw_tr_options' ), mstw_tr_get_defaults( ) );
			
			add_settings_section(
				$page_section,
				__( 'Data Fields & Columns', 'team-rosters' ),
				'mstw_tr_data_fields_inst',
				$display_on_page
				);

			$arguments = array(
				array( 	// Show/hide player PHOTOS
					'type' => 'show-hide', 
					'id' => 'show_photos',
					'name'	=> 'mstw_tr_options[show_photos]',
					'value' => mstw_tr_safe_ref( $options, 'show_photos' ), 
					'title' => __( 'Show Player Photos:', 'team-rosters' ),
					'desc'	=> __( 'Show Photos in roster tables" (Default: Hide)', 'team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// Player PHOTO LABEL
					'type' => 'text', 
					'id' => 'photo_label',
					'name'	=> 'mstw_tr_options[photo_label]',
					'value' => mstw_tr_safe_ref( $options, 'photo_label' ), 
					'title' => __( 'Photo Column Label:', 'team-rosters' ),
					'desc'	=> __( '(Default: Photo)', 'team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// Show/hide NUMBER column
					'type' => 'show-hide', 
					'id' => 'show_number',
					'name'	=> 'mstw_tr_options[show_number]',
					'value' => mstw_tr_safe_ref( $options, 'show_number' ), 
					'title' => __( 'Show Number:', 'team-rosters' ),
					'desc'	=> __( 'Show or hide the Number field/column. (Default: Show)', 'team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// NUMBER column label
					'type' => 'text', 
					'id' => 'number_label',
					'name'	=> 'mstw_tr_options[number_label]',
					'value' => mstw_tr_safe_ref( $options, 'number_label' ),
					'title'	=> __( 'Number Label:', 'team-rosters' ),
					'desc'	=> __( 'Label for the Number field/column. (Default: Nbr)', 'team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// NAME column label
					'type' => 'text', 
					'id' => 'name_label',
					'name'	=> 'mstw_tr_options[name_label]',
					'value' => mstw_tr_safe_ref( $options, 'name_label' ), 
					'title' => __( 'Name Label:', 'team-rosters' ),
					'desc'	=> __( 'Name field/column cannot be hidden. (Default: Name)', 'team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// Show/hide POSITION column
					'type' => 'show-hide', 
					'id' => 'show_position',
					'name'	=> 'mstw_tr_options[show_position]',
					'value' => mstw_tr_safe_ref( $options, 'show_position' ), 
					'title' => __( 'Show Position:', 'team-rosters' ),
					'desc'	=> __( 'Show or hide the Position field/column. (Default: Show)', 'team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// POSITION column label
					'type' => 'text', 
					'id' => 'position_label',
					'name'	=> 'mstw_tr_options[position_label]',
					'value' => mstw_tr_safe_ref( $options, 'position_label' ),
					'title'	=> __( 'Position Label:', 'team-rosters' ),
					'desc'	=> __( 'Label for the Position field/column. (Default: Pos)', 'team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// Show/hide HEIGHT column
					'type' => 'show-hide', 
					'id' => 'show_height',
					'name'	=> 'mstw_tr_options[show_height]',
					'value' => mstw_tr_safe_ref( $options, 'show_height' ), 
					'title' => __( 'Show Height:', 'team-rosters' ),
					'desc'	=> __( 'Show or hide the Height field/column. (Default: Show)', 'team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// HEIGHT column label
					'type' => 'text', 
					'id' => 'height_label',
					'name'	=> 'mstw_tr_options[height_label]',
					'value' => mstw_tr_safe_ref( $options, 'height_label' ),
					'title'	=> __( 'Height Label:', 'team-rosters' ),
					'desc'	=> __( 'Label for the Height field/column. (Default: Ht)', 'team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// Show/hide WEIGHT column
					'type' => 'show-hide', 
					'id' => 'show_weight',
					'name'	=> 'mstw_tr_options[show_weight]',
					'value' => mstw_tr_safe_ref( $options, 'show_weight' ), 
					'title' => __( 'Show Weight:', 'team-rosters' ),
					'desc'	=> __( 'Show or hide the Weight field/column. (Default: Show)', 'team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// WEIGHT column label
					'type' => 'text', 
					'id' => 'weight_label',
					'name'	=> 'mstw_tr_options[weight_label]',
					'value' => mstw_tr_safe_ref( $options, 'weight_label' ),
					'title'	=> __( 'Weight Label:', 'team-rosters' ),
					'desc'	=> __( 'Label for the Weight field/column. (Default: Wt)', 'team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// Show/hide YEAR column
					'type' => 'show-hide', 
					'id' => 'show_year',
					'name'	=> 'mstw_tr_options[show_year]',
					'value' => mstw_tr_safe_ref( $options, 'show_year' ), 
					'title' => __( 'Show Year:', 'team-rosters' ),
					'desc'	=> __( 'Show or hide the Year field/column. (Default: Hide)', 'team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// YEAR column label
					'type' => 'text', 
					'id' => 'year_label',
					'name'	=> 'mstw_tr_options[year_label]',
					'value' => mstw_tr_safe_ref( $options, 'year_label' ),
					'title'	=> __( 'Year Label:', 'team-rosters' ),
					'desc'	=> __( 'Label for the Year field/column. (Default: Year)', 'team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// Show/hide EXPERIENCE column
					'type' => 'show-hide', 
					'id' => 'show_experience',
					'name'	=> 'mstw_tr_options[show_experience]',
					'value' => mstw_tr_safe_ref( $options, 'show_experience' ), 
					'title' => __( 'Show Experience:', 'team-rosters' ),
					'desc'	=> __( 'Show or hide the Experience field/column. (Default: Hide)', 'team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// EXPERIENCE column label
					'type' => 'text', 
					'id' => 'experience_label',
					'name'	=> 'mstw_tr_options[experience_label]',
					'value' => mstw_tr_safe_ref( $options, 'experience_label' ),
					'title'	=> __( 'Experience Label:', 'team-rosters' ),
					'desc'	=> __( 'Label for the Experience field/column. (Default: Exp)', 'team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// Show/hide AGE column
					'type' => 'show-hide', 
					'id' => 'show_age',
					'name'	=> 'mstw_tr_options[show_age]',
					'value' => mstw_tr_safe_ref( $options, 'show_age' ), 
					'title' => __( 'Show Age:', 'team-rosters' ),
					'desc'	=> __( 'Show or hide the Age field/column. (Default: Hide)', 'team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// AGE column label
					'type' => 'text', 
					'id' => 'age_label',
					'name'	=> 'mstw_tr_options[age_label]',
					'value' => mstw_tr_safe_ref( $options, 'age_label' ),
					'title'	=> __( 'Age Label:', 'team-rosters' ),
					'desc'	=> __( 'Label for the Age field/column. (Default: Age)', 'team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// Show/hide HOME TOWN column
					'type' => 'show-hide', 
					'id' => 'show_home_town',
					'name'	=> 'mstw_tr_options[show_home_town]',
					'value' => mstw_tr_safe_ref( $options, 'show_home_town' ), 
					'title' => __( 'Show Home Town:', 'team-rosters' ),
					'desc'	=> __( 'Show or hide the Home Town field/column. (Default: Hide)', 'team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// HOME TOWN column label
					'type' => 'text', 
					'id' => 'home_town_label',
					'name'	=> 'mstw_tr_options[home_town_label]',
					'value' => mstw_tr_safe_ref( $options, 'home_town_label' ),
					'title'	=> __( 'Home Town Label:', 'team-rosters' ),
					'desc'	=> __( 'Label for the Home Town field/column. (Default: Home Town)', 'team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// Show/hide LAST SCHOOL column
					'type' => 'show-hide', 
					'id' => 'show_last_school',
					'name'	=> 'mstw_tr_options[show_last_school]',
					'value' => mstw_tr_safe_ref( $options, 'show_last_school' ), 
					'title' => __( 'Show Last School:', 'team-rosters' ),
					'desc'	=> __( 'Show or hide the Last School field/column. (Default: Hide)', 'team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// LAST SCHOOL column label
					'type' => 'text', 
					'id' => 'last_school_label',
					'name'	=> 'mstw_tr_options[last_school_label]',
					'value' => mstw_tr_safe_ref( $options, 'last_school_label' ),
					'title'	=> __( 'Last School Label:', 'team-rosters' ),
					'desc'	=> __( 'Label for the Last School field/column. (Default: Last School)', 'team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// Show/hide COUNTRY column
					'type' => 'show-hide', 
					'id' => 'show_country',
					'name'	=> 'mstw_tr_options[show_country]',
					'value' => mstw_tr_safe_ref( $options, 'show_country' ), 
					'title' => __( 'Show Country:', 'team-rosters' ),
					'desc'	=> __( 'Show or hide the Country field/column. (Default: Hide)', 'team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// COUNTRY column label
					'type' => 'text', 
					'id' => 'country_label',
					'name'	=> 'mstw_tr_options[country_label]',
					'value' => mstw_tr_safe_ref( $options, 'country_label' ),
					'title'	=> __( 'Country Label:', 'team-rosters' ),
					'desc'	=> __( 'Label for the Country field/column. (Default: Country)', 'team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// Show/hide BATS/THROWS column
					'type' => 'show-hide', 
					'id' => 'show_bats_throws',
					'name'	=> 'mstw_tr_options[show_bats_throws]',
					'value' => mstw_tr_safe_ref( $options, 'show_bats_throws' ), 
					'title' => __( 'Show Bats/Throws:', 'team-rosters' ),
					'desc'	=> __( 'Show or hide the Bats/Throws field/column. (Default: Hide)', 'team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// BATS/THROWS column label
					'type' => 'text', 
					'id' => 'bats_throws_label',
					'name'	=> 'mstw_tr_options[bats_throws_label]',
					'value' => mstw_tr_safe_ref( $options, 'bats_throws_label' ),
					'title'	=> __( 'Bats/Throws Label:', 'team-rosters' ),
					'desc'	=> __( 'Label for the Bats/Throws field/column. (Default: Bat/Thw)', 'team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// Show/hide OTHER INFO column
					'type' => 'show-hide', 
					'id' => 'show_other_info',
					'name'	=> 'mstw_tr_options[show_other_info]',
					'value' => mstw_tr_safe_ref( $options, 'show_other_info' ), 
					'title' => __( 'Show Other Info:', 'team-rosters' ),
					'desc'	=> __( 'Show or hide the Other Info field/column. (Default: Hide)', 'team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
				array( 	// OTHER INFO column label
					'type' => 'text', 
					'id' => 'other_info_label',
					'name'	=> 'mstw_tr_options[other_info_label]',
					'value' => mstw_tr_safe_ref( $options, 'other_info_label' ),
					'title'	=> __( 'Other Info Label:', 'team-rosters' ),
					'desc'	=> __( 'Label for the Other Info field/column. (Default: Other)', 'team-rosters' ),
					'page' => $display_on_page,
					'section' => $page_section,
				),
			);
			
			mstw_tr_build_settings_screen( $arguments );
			
		} //End: mstw_tr_data_fields_columns_setup()
	} //End: mstw_tr_data_fields_section_setup()
	
//-----------------------------------------------------------------	
// 	Colors table section instructions	
//	
	if( !function_exists( 'mstw_tr_data_fields_inst' ) ) {
		function mstw_tr_data_fields_inst( ) {
			echo '<p>' . __( 'Settings to control the visibility of data fields & table columns as well as to change their labels to "re-purpose" the fields. ', 'team-rosters' ) .'</p>';
		} //End: mstw_tr_data_fields_inst()
	}