<?php
/* Localization */
function g_dictionary()
{
	static $g_loc = array(
		'en' => array(
			'chapter' => 'Chapter',
			'prev' => 'Prev',
			'next' => 'Next',
                        'home' => 'Home'
		),
		'ru' => array(
			'chapter' => 'Глава',
			'prev' => 'Взад',
			'next' => 'Вперед',
                        'home' => 'Домой'
		)

	);
	return $g_loc;
}

/* Pretend that you didn't see it */
function g_translate($ln,$str)
{
	$lns = g_dictionary();
	if ( isset($lns[$ln][$str]) ) return $lns[$ln][$str];
        if (IS_DEBUGGING) $str .= WORD_NOT_FOUND; 
	return $str;	
}
?>
