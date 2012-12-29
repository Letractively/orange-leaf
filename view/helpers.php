<?php

function getPagingButtons($stat)
{
//	if (empty($stat) || count($stat) < 3) {
//		return array();
//	}
//        
//	$stat = $stat;
	$prev_offset = false;
	if ($stat['offset'] > 0) {
		$prev_offset =  $stat['offset'] - $stat['elements_on_page'];
		if ($prev_offset < 0) {
			$prev_offset = 0;
		}
	}
		
	$next_offset = false;
	if ( ($stat['offset'] + $stat['elements_on_page']) < $stat['total_elements'] ) {
		$next_offset =  $stat['offset'] + $stat['elements_on_page'];
	}
	
	$pages = array();
	$delta = ($stat['elements_on_page']) ? $stat['elements_on_page'] : 1; 
	for ($i = 0; $i < $stat['total_elements']; $i += $delta) {		
		$pages[]=$i;
	}
	
	return array(
			'prev' => $prev_offset,
			'next' => $next_offset,
			'current' => $stat['offset'],
			'pages' => $pages
	);
}
 
function getImagePagingButtons($o)
{
	if (!($o->extra('image'))) {
		throw new Error500('Wrong argument, "image" missing');
	}
	$image = $o->extra('image');
        $stat = $o->stat();
        $elements = $o->elements();
        
	$pages = array();
	$current_image_id = -1;
	$current_image_name = $image['filename'];
	
	for ($i = 0; $i < $stat['total_elements']; $i++ ) {
		if (!isset($elements[$i]))
			return array();
		$pages[] = $elements[$i]['filename'];
		if ($current_image_name == $elements[$i]['filename']) {
			$current_image_id = $i;
		}
	}
	
	if (-1 === $current_image_id)
		throw new Error500('Internal error, can\'t find current image id. ');
		
	$prev_offset = ($current_image_id > 0) ? $pages[$current_image_id - 1] : false;
	$next_offset = ($current_image_id < $stat['total_elements']-1) ? $pages[$current_image_id +1] : false;
	
	return array(
		'prev' => $prev_offset,
		'next' => $next_offset,
		'current' => $current_image_name,
		'pages' => $pages,
		'current_num' => $current_image_id
	);
}


