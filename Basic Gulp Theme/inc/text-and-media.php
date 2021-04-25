<?php

// Pagination:
function paginate( $the_post_type, $number = 12, $tax_q = '', $actual_page = 1, $actual_link = null ) {
	if( !is_null($actual_link) && !empty($actual_link) ){
		$args = array(
			'posts_per_page' => -1,
			'post_type' => $the_post_type,
			'order_by' => 'date',
			'order' => 'DESC',
			'suppress_filters' => false,
			'tax_query' => $tax_q
		);
		$wp_query_count = new WP_Query( $args );
		$post_count = $wp_query_count->post_count;
		if( $post_count > $number ){
			echo "<div class='container pagination-block'><div class='navigation'><ul>";
			$all_pages = (int)$post_count / $number;
			if($actual_page != 1 && $all_pages > 3){
				if($actual_page > 1 ){
					$actual_link_first = $actual_link . 'paged/'.($actual_page-1).'/';
					if($actual_page == 2){
						$actual_link_first = $actual_link;
					}
				}
				echo "<li><a href='".$actual_link_first."'><i class='fa fa-chevron-left'></i></a></li>";
			}
			if( ($actual_page-1) >=1 ){
				$actual_link_n1 = $actual_link . 'paged/'.($actual_page-1).'/';
				if(($actual_page-1) == 1){
					$actual_link_n1 = $actual_link;
				}
				echo "<li><a href='".$actual_link_n1."'>".($actual_page-1)."</a></li>";
			}
			if($all_pages >= 1){
				$actual_link_n1 = $actual_link . 'paged/'.$actual_page.'/';
				if($actual_page == 1){
					$actual_link_n1 = $actual_link;
				}
				echo "<li><a class='active' href='".$actual_link_n1."'>".$actual_page."</a></li>";
			}
			if($all_pages >= 2 && $actual_page < $all_pages && ($actual_page + 1) < $all_pages){
				$actual_link_n2 = $actual_link . 'paged/'.($actual_page+1).'/';
				echo "<li><a href='".$actual_link_n2."'>".($actual_page + 1)."</a></li>";
			}
			if($all_pages >= 3 && $actual_page == 1 ){
				$act_page = $actual_page+2;
				$actual_link_n3 = $actual_link . 'paged/'.$act_page.'/';
				echo "<li><a href='".$actual_link_n3."'>".$act_page."</a></li>";
			}
			if($all_pages > 3 && $actual_page < $all_pages){
				$actual_link = $actual_link . 'paged/'.($actual_page + 1).'/';
				echo "<li><a href='".$actual_link."'><i class='fa fa-chevron-right'></i></a></li>";
			}
			echo "</ul></div></div>\n";
		}
	}
	/*echo "<div class=\"navigation\"><ul>";
	if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<li><a href='".get_pagenum_link(1)."'>&laquo; Prima</a></li>\n";
	if($paged > 1 && $showitems < $pages) echo "<li><a class=\"previous\" href='".get_pagenum_link($paged - 1)."'>&lsaquo; Precedente</a></li>";
	for ($i=1; $i <= $pages; $i++){
		if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )){
			echo ($paged == $i)? "<li class=\"active\"><span>".$i."</span></li>":"<li>\n<a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a></li>\n";
		}
	}
	if($paged < $pages && $showitems < $pages) echo "<li><a href=\"".get_pagenum_link($paged + 1)."\" style='margin-right:3px;'>Successiva &rsaquo;</a></li>";
	if($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<li><a href='".get_pagenum_link($pages)."'>Ultima &raquo;</a></li>\n";
	echo "</ul></div>\n";*/
}
// --------------------------------------------------------

function get_optimized_image($array_image, $default_size="large") {
	if($array_image && is_array($array_image) && count($array_image) > 0) {
		switch ($default_size) {
			case "original":
				return isset($array_image["url"]) ? $array_image["url"] : false;
				break;
			case "1536x1536":
				return isset($array_image["sizes"][$default_size]) ? $array_image["sizes"][$default_size] : $array_image["url"];
				break;
			case "large":
				return isset($array_image["sizes"][$default_size]) ? $array_image["sizes"][$default_size] : $array_image["url"];
				break;
			case "medium":
				return isset($array_image["sizes"][$default_size]) ? $array_image["sizes"][$default_size] : $array_image["url"];
				break;
			case "small":
				return isset($array_image["sizes"][$default_size]) ? $array_image["sizes"][$default_size] : $array_image["url"];
				break;
			case "thumbnail":
				return isset($array_image["sizes"][$default_size]) ? $array_image["sizes"][$default_size] : $array_image["url"];
				break;
			default:
				return isset($array_image["url"]) ? $array_image["url"] : false;
		}
	}
}



