<?php

function get_pages_by_template( $template_page = null ){
	if( !is_null($template_page) ){
		$pages_tpl = get_pages( array(
			'meta_key' => '_wp_page_template',
			'meta_value' => $template_page
		));
	}
	return $pages_tpl;
}

function get_breadcrumb() {
	echo "<ul>";
		echo '<li><a title="Homepage - Lady Bacardi" href="'.home_url().'" rel="nofollow">Home / </a></li>';
		if(is_singular('films')){
			$pages_cinema = get_pages_by_template('tpl-archive-cinema.php');
			if( $pages_cinema && is_array($pages_cinema) && count($pages_cinema) >0 && isset($pages_cinema[0]->ID) ){
				echo "<li><a href=" . get_the_permalink($pages_cinema[0]->ID) . " title=".get_the_title($pages_cinema[0]->ID).">" . get_the_title($pages_cinema[0]->ID) . " / </a></li>";
			}
		}
		if( is_tax() ){
			global $wp;
			$actual_link = rtrim( home_url( $wp->request ), '/') . '/' ;
			echo "<li><a href=" . $actual_link . " title=".get_queried_object()->name.">".get_queried_object()->name."</a></li>";
		}
		if(!is_tax()){
			if(is_page()){
				$id_p = wp_get_post_parent_id(get_the_ID());
				if( $id_p && $id_p != 0 ){
					echo "<li><a href=" . get_the_permalink($id_p) . " title=".get_the_title($id_p).">" . get_the_title($id_p) . " / </a></li>";
				}
			}
			echo "<li><a href=" . get_the_permalink(get_the_ID()) . " title=".get_the_title(get_the_ID()).">" . get_the_title(get_the_ID()) . "</a></li>";
		}
    echo "</ul>";
}


function my_flag_only_language_switcher() {
    $all_languages = apply_filters( 'wpml_active_languages', NULL, '' );
	if($all_languages && is_array($all_languages)){
		return $all_languages;
	}else{
		$all_languages = icl_get_languages('skip_missing=N&orderby=KEY');
		return $all_languages;
	}
}


function retrieve_link( $array_infos = null){
	$results = array(
		'url' => '',
		'label' => __('Read More', 'ldb'),
		'target' => ''
	);
	if( isset($array_infos['link_label']) & !empty($array_infos['link_label']) ){
		$results['label'] = $array_infos['link_label'];
	}
	if( !is_null($array_infos) && is_array($array_infos) && isset($array_infos['has_link']) ){
		if( isset($array_infos['link_type']) && $array_infos['link_type'] == 'Int' ){

			$results['url'] = isset($array_infos['internal_link'][0]->ID) ? get_the_permalink($array_infos['internal_link'][0]->ID) : site_url();

		}else if( isset($array_infos['link_type']) && $array_infos['link_type'] == 'Ext' ){

			if( isset($array_infos['use_link_text']) && $array_infos['use_link_text'] == 'Y' ){
				$results['label'] = isset($array_infos['external_link']['title']) && !empty($array_infos['external_link']['title']) ? $array_infos['external_link']['title'] : $array_infos['link_label'];
			}
			$results['url'] = isset($array_infos['external_link']['url']) && !empty($array_infos['external_link']['url']) ? $array_infos['external_link']['url'] : site_url();
			if( isset($array_infos['external_link']['target']) ){
				$results['target'] = $array_infos['external_link']['target'];
			}

		}
	}
	return $results;
}

function get_carousel_infos( $infos = null, $car_name = null ){
	if( !is_null($infos) && is_array($infos) && !is_null($car_name) && !empty($car_name) ){
		$car_info = get_carousel_values( $car_name, $infos );
		wp_register_script( 'carousel_handle', get_template_directory_uri() . '/js/main_carousel.js' );
		wp_localize_script( 'carousel_handle', 'carousel_infos_' . $car_name, $car_info );
		return true;
	}
}

function get_carousel_values( $car_name = null, $infos ){
	if( !is_null($car_name) && !empty($car_name) ){
		$default = array(
			'items' => '',
			'loop' => '',
			'mouseDrag' => '',
			'touchDrag' => '',
			);
		switch ($car_name){
			case 'photoCarousel':
			$default = array(
				'items' => count($infos) > 3 ? 5 : 3,
				'loop' => count($infos) > 5 ? json_encode(true) : json_encode(false),
				'mouseDrag' => count($infos) > 5 ? json_encode(true) : json_encode(false),
				'touchDrag' => count($infos) > 5 ? json_encode(true) : json_encode(false),
				'center' => count($infos) < 5 ? json_encode(false) : json_encode(true),
				'margin_auto' => count($infos) >= 5 ? json_encode(false) : json_encode(true),
				'responsive' => array(
					0 => 1,
					768 => count($infos) > 3 ? 3 : count($infos),
					991 => count($infos) > 3 ? 5 : 3
				)
			);
			return $default;
			case 'generalCarousel':
			$default = array(
				'items' => count($infos) > 3 ? 3 : count($infos),
				'loop' => count($infos) >= 3 ? json_encode(true) : json_encode(false),
				'mouseDrag' => count($infos) >= 3 ? json_encode(true) : json_encode(false),
				'touchDrag' => count($infos) >= 3 ? json_encode(true) : json_encode(false),
				'center' => count($infos) < 3 ? json_encode(false) : json_encode(true),
				'margin_auto' => count($infos) >= 3 ? json_encode(false) : json_encode(true),
				'responsive' => array(
					0 => 1,
					768 => count($infos) > 2 ? 2 : count($infos),
					991 => count($infos) > 3 ? 3 : count($infos)
				)
			);
			return $default;
			case 'cinemaCarousel':
			$default = array(
				'items' => count($infos) > 5 ? 5 : count($infos),
				'loop' => count($infos) >= 5 ? json_encode(true) : json_encode(false),
				'mouseDrag' => count($infos) > 5 ? json_encode(true) : json_encode(false),
				'touchDrag' => count($infos) > 5 ? json_encode(true) : json_encode(false),
				'center' => count($infos) < 5 ? json_encode(false) : json_encode(true),
				'margin_auto' => count($infos) >= 5 ? json_encode(false) : json_encode(true),
				'responsive' => array(
					0 => 1,
					768 => count($infos) > 3 ? 3 : count($infos),
					1201 => count($infos) > 5 ? 5 : count($infos)
				)
			);
			return $default;
			case 'homeCarousel':
			$count = isset($infos[0]) && is_array($infos[0]) ? count($infos) : 1;
			$default = array(
				'items' => $count > 1 ? 1 : $count,
				'loop' => $count > 1 ? json_encode(true) : json_encode(false),
				'mouseDrag' => $count > 1 ? json_encode(true) : json_encode(false),
				'touchDrag' => $count > 1 ? json_encode(true) : json_encode(false),
				'center' => $count < 1 ? json_encode(false) : json_encode(true),
				'margin_auto' => false,
				'responsive' => array(
					0 => 1,
					768 => 1,
					991 => 1
				)
			);
			return $default;
			case 'iconsCarousel':
			$default = array(
				'items' => count($infos) > 5 ? 5 : count($infos),
				'loop' => count($infos) > 5 ? json_encode(true) : json_encode(false),
				'mouseDrag' => count($infos) > 5 ? json_encode(true) : json_encode(false),
				'touchDrag' => count($infos) > 5 ? json_encode(true) : json_encode(false),
				'responsive' => array(
					0 => 3,
					768 => 3,
					991 => 5
				)
			);
			return $default;
			case 'castCarousel':
			$default = array(
				'items' => count($infos) > 4 ? 4 : count($infos),
				'loop' => count($infos) > 4 ? json_encode(true) : json_encode(false),
				'mouseDrag' => count($infos) > 4 ? json_encode(true) : json_encode(false),
				'touchDrag' => count($infos) > 4 ? json_encode(true) : json_encode(false),
				'center' => count($infos) <= 4 ? json_encode(false) : json_encode(true),
				'margin_auto' => count($infos) >= 4 ? json_encode(false) : json_encode(true),
			);
			return $default;
			default:
				return false;
		}
	}
}

function return_related_posts( $is_homepage = 'N', $auto_rel = 'Y', $auto_cat = '', $relations = null, $post_number = 4, $paged = 1 ){
	$cats = false;
	$tax_q = array(array(
		'taxonomy' => 'news-categories',
		'field' => 'ID',
		'terms' => array(),
	));
	$args = array(
		'posts_per_page' => $post_number,
		'post_type' => 'news-area',
		'post_status' => 'publish',
		'order_by' => 'date',
		'order' => 'DESC',
		'suppress_filters' => false,
		'tax_query' => $tax_q,
		'paged' => $paged
	);
	if($is_homepage == 'N'){
		if($auto_rel == 'Y'){
			if( !$auto_cat || is_null($auto_cat) || empty($auto_cat) ){
				global $template;
				$current_tpl = basename($template);
				$current_page_with_tpl = get_pages_by_template($current_tpl);
				if(is_array($current_page_with_tpl) && count($current_page_with_tpl) > 0){
					foreach( $current_page_with_tpl as $keyCP => $singleCP ){
						if( get_the_ID() == $singleCP->ID ){
							if( isset($singleCP->meta_value) && !empty($singleCP->meta_value) && strpos($singleCP->meta_value, '.php') !== false ){
								$auto_cat = get_the_relation_with_template($singleCP->meta_value);
								$the_final_term = get_term_by('slug', $auto_cat, 'news-categories');
								if($the_final_term && is_object($the_final_term)){
									$tax_q[0]['terms'] = array($the_final_term->term_id);
									$args['tax_query'] = $tax_q;
									return get_posts($args);
								}
							}
						}
					}
				}
			}
			$tax_q[0]['terms'] = array($auto_cat);
			$args['tax_query'] = $tax_q;
			return get_posts($args);
		}else{
			if( !is_null($relations) ){
				$rel_posts = $relations;
				return $rel_posts;
			}else{
				$id_term = get_term_by('slug', $auto_cat, 'news-categories');
				$id_term = isset($id_term->term_id) ? $id_term->term_id : false;
				$tax_q[0]['terms'] = array($id_term);
				$args['tax_query'] = $tax_q;
				return get_posts($args);
			}
		}
	}else{
		if($auto_rel == 'Y'){
			$args['tax_query'] = '';
			return get_posts($args);
		}else{
			$rel_posts = $relations;
			return $rel_posts;
		}
	}
	return false;
}

function get_the_relation_with_template($tpl_name = ''){
	$tpls = array(
		'tpl-archive-cinema.php' => 'cinema',
		'tpl-charity.php' => 'charity',
		'tpl-archive-ars-culture.php' => 'ars-culture',
		'tpl-biography.php' => 'biography'
	);
	return isset($tpls[$tpl_name]) ? $tpls[$tpl_name] : false ;
}

function get_info_from_acf_opt( $custom_block, $is_opt_page = false, $opt_page_name = false ) {
	global $post_type;
	$archive_info = false;
	$dynamic_field_name = 'options-' . $post_type;

	if( $is_opt_page && $opt_page_name && !empty($opt_page_name) ) {
		$dynamic_field_name = $opt_page_name;
	}

	$archive_info = get_field($custom_block, $dynamic_field_name . '_' . ICL_LANGUAGE_CODE);
	if( is_null($archive_info) || empty($archive_info) ){
		$archive_info = get_field($custom_block, $dynamic_field_name . '_en');
	}
	return $archive_info;
}

function get_url_noGET() {

    global $wp;

    $current_url =  home_url( $wp->request );
    $position = strpos( $current_url , '/paged' );
    $nopaging_url = ( $position ) ? substr( $current_url, 0, $position ) : $current_url;

    return trailingslashit( $nopaging_url );

}

function reOrderCats( $arr_cat = null, $order = null ){
	if( !is_null($arr_cat) && is_array($arr_cat) && !is_null($order) && is_array($order) ){
		$new_arr_cat = array();
		foreach( $order as $value ){
			$new_arr_cat[] = $arr_cat[$value];
		}
		return $new_arr_cat;
	}
}

function update_locandine_EN(){
	if( !is_admin() ){
		include_once (get_theme_root() .  '/ladybacardi/inc/simpleXLSX.php' );
		$xlsx = new SimpleXLSX('Locandine-Export.xlsx');
		if($xlsx->rows()){
			$sheet = $xlsx->rows();
		}
		if(is_array($sheet) && count($sheet) > 0){
			global $wpdb;
			foreach( $sheet as $key => $value){
				if( $key != 0 && $value[4] != '' ){
					$info_film = get_field('film', $value[0]);
					$id_locandina = is_array($info_film['film_poster']) && isset($info_film['film_poster']['ID']) ? $info_film['film_poster']['ID'] : false;
					if($id_locandina){
						$execut = $wpdb->update( $wpdb->posts,
						array(
							'post_title'		=> $value[5],
							'post_excerpt'		=> $value[6],
						),
						array( 'ID' => $id_locandina ),
						array(
							'%s',
							'%s',
						),
						array( '%d' )
						);
						$execut_ins = $wpdb->insert( $wpdb->postmeta, array('post_id'=>$id_locandina, 'meta_key'=> '_wp_attachment_image_alt', 'meta_value' => $value[7]), array( '%d', '%s', '%s' ) );
					}
				}
			}
		}
	}
}

function  generate_json_actors(){
	$json_args = array(
		'posts_per_page' => -1,
		'post_type' => 'cast',
		'order_by' => 'title',
		'order' => 'ASC',
		'suppress_filters' => false
	);
	$actor_infos =  get_posts( $json_args );
	$actor_array = array();
	foreach( $actor_infos as $keyA => $singleA ){
		$actor_array[$keyA]['id'] = (string)$singleA->ID;
		$actor_array[$keyA]['label'] = $singleA->post_title;
		$actor_array[$keyA]['value'] = $singleA->post_name;
	}
	//comment these two lines when errors are resolved
	error_reporting(E_ALL);
	ini_set('display_errors',1);

	$json = $actor_array; //json need to be data
	$info = json_encode($json);
	$file = fopen('actors.json','w+') or die("File not found");
	fwrite($file, $info);
	fclose($file);
}

function get_current_actors( $taxq = null ){
	if( !is_null($taxq) ){
		$args = array(
			'posts_per_page' => -1,
			'post_type' => 'films',
			'order_by' => 'date',
			'order' => 'DESC',
			'suppress_filters' => false,
			'tax_query' => $taxq
		);
		$film_infos =  get_posts( $args );
	}
	if( !is_null($film_infos) && is_array($film_infos) && count($film_infos) >0 ){
		$array_of_actors = array();
		foreach($film_infos as $keySF => $single_film){
			$cast_relation = get_field('cast_relation', $single_film->ID);
			foreach( $cast_relation as $keyC => $singleC ){
				if( !isset($array_of_actors[$singleC->ID]) ){
					$array_of_actors[$singleC->ID]['id'] = (string)$singleC->ID;
					$array_of_actors[$singleC->ID]['label'] = $singleC->post_title;
					$array_of_actors[$singleC->ID]['value'] = $singleC->post_name;
				}
			}
		}
		$json = $array_of_actors; //json need to be data
		$info = json_encode($json);
		$file = fopen('actors.json','w+') or die("File not found");
		fwrite($file, $info);
		fclose($file);
		return true;
	}
	return false;
}

function get_current_years( $taxq = null, $meta_q = '' ){
	if( !is_null($taxq) ){
		$args = array(
			'posts_per_page' => -1,
			'post_type' => 'films',
			'order_by' => 'date',
			'order' => 'DESC',
			'suppress_filters' => false,
			'tax_query' => $taxq,
			'meta_query' => $meta_q
		);
		$film_infos =  get_posts( $args );
	}
	if( !is_null($film_infos) && is_array($film_infos) && count($film_infos) >0 ){
		$array_of_years = array();
		foreach($film_infos as $keySF => $single_film){
			$film_infos = get_field('film', $single_film->ID);
			if( !in_array($film_infos['year'], $array_of_years) ){
				$array_of_years[] = $film_infos['year'];
			}
		}
		rsort($array_of_years);
		return $array_of_years;
	}
}

function get_current_genres( $taxq = null, $metaq = ''  ){
	if( !is_null($taxq) ){
		$args = array(
			'posts_per_page' => -1,
			'post_type' => 'films',
			'order_by' => 'date',
			'order' => 'DESC',
			'suppress_filters' => false,
			'tax_query' => $taxq,
			'meta_query' => $metaq
		);
		$film_infos =  get_posts( $args );
	}
	if( !is_null($film_infos) && is_array($film_infos) && count($film_infos) >0 ){
		$array_of_genres = array();
		foreach($film_infos as $keySF => $single_film){
			$rel_category = get_the_terms( $single_film->ID, "film-genre" );
			foreach( $rel_category as $keyRL => $singleRL ){
				if( !isset($array_of_genres[$singleRL->term_id]) ){
					$array_of_genres[$singleRL->term_id]['slug'] = $singleRL->slug;
					$array_of_genres[$singleRL->term_id]['label'] = $singleRL->name;
				}
			}
		}
		asort($array_of_genres);
		return $array_of_genres;
	}
}