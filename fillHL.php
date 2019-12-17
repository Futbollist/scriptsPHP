<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$entity_data_class_likes = \Spichka\General::GetEntityDataClass(ID_HIGHLOAD_LIKE);

$rsDataLikes = $entity_data_class_likes::getList(array(
	'select' => array('*'),
	'filter' => array(
    'UF_DATA_LIKES' => ''
	)
));


while($arData = $rsDataLikes->Fetch()){
    $entity_data_class_likes::update($arData["ID"], array(
      'UF_DATA_LIKES' => date('d.m.Y H:i')
    ));
}

$entity_data_class_shares = \Spichka\General::GetEntityDataClass(ID_HIGHLOAD_SHARE);

$rsDataShares = $entity_data_class_shares::getList(array(
	'select' => array('*'),
	'filter' => array(
    'UF_DATA_SHARES' => ''
	)
));

while($arData = $rsDataShares->Fetch()){
    $entity_data_class_shares::update($arData["ID"], array(
      'UF_DATA_SHARES' => date('d.m.Y H:i')
    ));
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>