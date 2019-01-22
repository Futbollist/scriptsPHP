<?php

	$lowerURI=strtolower($_SERVER['REQUEST_URI']);
	if($_SERVER['REQUEST_URI'] != $lowerURI) {
		header("Location: http://" . $_SERVER['HTTP_HOST'] . $lowerURI);
		exit();
	}

	if(strpos($_SERVER['REQUEST_URI'], '/index.php') !== false) {
		$withoutIndex = str_ireplace("/index.php", "/", $_SERVER['REQUEST_URI']);
		header("Location: http://" . $_SERVER['HTTP_HOST'] . $withoutIndex);
		exit();
	}

	$redirects = array(
			'http://site/diski_lipetsk' => 'http://geekbrains/diski',
			'http://site/shiny_lipetsk' => 'http://site/shiny',
			'http://site/akkumuljatory_lipetsk' => 'http://site',
			'http://site/avtomobilnye_kovriki_lipetsk' => 'http://site/izgotovlenie-avtokovrikov',
			'http://site/shinomontazh_lipetsk' => 'http://site/shinomontazh',
			'http://site/avtomojka_lipetsk' => 'http://site/avtomoyka',
			'http://site/blog' => 'http://site/news',
			'http://site/contacts' => 'http://site/kontakty',
			'http://site/kak_vybrat_shiny' => 'http://site/news/kak-vybrat-shiny',
			'http://site/kak_vybrat_diski' => 'http://site/news/kak-vybrat-diski',
			'http://site/tovar_pod_zakaz' => 'http://site/news/tovar-pod-zakaz',
			'http://site/blog/ekspress-himchistka-za-1-chas---2500-rub' => 'http://site/news',
			'http://site/blog/samye-nizkie-tseny-na-shiny' => 'http://site/news/samye-nizkie-ceny-na-shiny/',
			'http://site/blog/skidka-12--na-litye-diski' => 'http://site/news',
			'http://site/blog/zhidkoe-steklo' => 'http://site/news/zhidkoe-steklo/',
			'http://site/blog/himchistka-lyubyh-kovrovyh-pokrytij-s-dostavkoj' => 'http://site/news',
			'http://site/blog/zaklyuchaem-dogovora-na-okazanie-uslug' => 'http://site/news',
			'http://site/blog/gotovimsya-k-vesne' => 'http://site/news',
			'http://site/blog/aktsiya-na-masla' => 'http://site/news',
			'http://site/blog/soobschestvo-v-kontakte-avtostandart-otmechaet-svoj-1-yj-den-rozhdeniya-' => 'http://site/news',
			'http://site/blog/aktsiya-shinylitye-diski-rassrochka-bez-pervonachalnogo-vznosa' => 'http://site/news',
			'http://site/blog/kolesa-v-sbore--6-mesyatsev-rassrochka' => 'http://site/news',
			'http://site/blog/avtozapchasti-dlya-inomarok' => 'http://site/news',
			'http://site/blog/dostavka-shin-diskov-akb-do-mesta' => 'http://site/news/dostavka-shin-diskov-akb/',
			'http://site/fotogalereya' => 'http://site/o-magazine/'
		);

	$current_url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	if ( ! empty($redirects[$current_url]) ) {
		$redirect_to = $redirects[$current_url];
		header("Location: $redirect_to");
		exit();
	}

?>