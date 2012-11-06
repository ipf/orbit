<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'Ipf' . $_EXTKEY,
	'Orbit',
	'Orbit Slideshow'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Orbit Slideshow');

t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['columns']['CType']['config']['items'][] = array('Orbit Slideshow', 'orbit');
$TCA['tt_content']['types']['orbit'] = array(
	'showitem' => '--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.general;general, --palette--;LLL:EXT:cms/locallang_ttc.xml:palette.header;header;LLL:EXT:cms/locallang_ttc.xml:tabs.images, image, --palette--;LLL:EXT:cms/locallang_ttc.xml:palette.imagelinks;imagelinks,--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access, --palette--;LLL:EXT:cms/locallang_ttc.xml:palette.visibility;visibility, --palette--;LLL:EXT:cms/locallang_ttc.xml:palette.access;access'
);
?>