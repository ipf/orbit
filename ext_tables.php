<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Orbit',
	'Orbit Slideshow'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Orbit Slideshow');

$pluginSignature = str_replace('_', '', $_EXTKEY) . '_orbit';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/Orbit.xml');


/*\TYPO3\CMS\Core\Utility\GeneralUtility::loadTCA('tt_content');
$TCA['tt_content']['columns']['CType']['config']['items'][] = array('Orbit Slideshow', 'orbit');
$TCA['tt_content']['types']['orbit'] = array(
	'showitem' => '--palette--;LLL:EXT:cms/locallang_ttc.xml:palette.general;general, --palette--;LLL:EXT:cms/locallang_ttc.xml:palette.header;header;LLL:EXT:cms/locallang_ttc.xml:tabs.images, image, --palette--;LLL:EXT:cms/locallang_ttc.xml:palette.imagelinks;imagelinks,--div--;LLL:EXT:cms/locallang_ttc.xml:tabs.access, --palette--;LLL:EXT:cms/locallang_ttc.xml:palette.visibility;visibility, --palette--;LLL:EXT:cms/locallang_ttc.xml:palette.access;access'
);*/
?>