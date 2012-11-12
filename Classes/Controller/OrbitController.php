<?php
namespace Ipf\Orbit\Controller;
/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Ingo Pfennigstorf <i.pfennigstorf@gmail.com>
 *  
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

/**
 * Controller for orbit slideshsow
 */
class OrbitController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * @var TYPO3\CMS\Core\Resource\FileRepository
	 * @inject
	 */
	protected $fileRepository;


	/**
	 * File Collection Repository
	 * @inject
	 *
	 * @var TYPO3\CMS\Core\Resource\FileCollectionRepository
	 */
	protected $fileCollectionRepository;


	public function initializeAction() {
		$this->fileRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\FileRepository');
	}

	/**
	 * Get all images from the current content element, process them and hand them
	 * over to the view
	 *
	 * @return void
	 */
	public function indexAction() {
		$fileCollections = $this->getAvailableFileCollections();

			// redirect to the single-gallery view if there is only one collection and this one
			// is actually set by the settings (flexforms or TS)
		if (count($fileCollections) === 1 && $this->settings['fileCollections'] != '') {
			$fileCollection = reset($fileCollections);
			$this->forward('showGallery', NULL, NULL, array('fileCollectionUid' => $fileCollection->getIdentifier()));
		}

		$this->view->assign('fileCollections', $fileCollections);
	}


	/**
	 * Show all images of a single collection
	 *
	 * @param int $fileCollectionUid
	 * @param int $page the page number
	 * @return void
	 */
	public function showGalleryAction($fileCollectionUid, $page = 1) {
		$fileCollections = $this->getAvailableFileCollections();
		$showOverviewLink = !(count($fileCollections) === 1 && $this->settings['fileCollections'] != '');

		/** @var $fileCollection t3lib_file_Collection_StaticFileCollection */
		$fileCollection = $this->fileCollectionRepository->findByUid($fileCollectionUid);
		$fileCollection->loadContents();
		$this->view->assign('fileCollection', $fileCollection);
		$this->view->assign('showOverviewLink', $showOverviewLink);
		$this->view->assign('galleryUid', $fileCollectionUid);

			// if the lightbox is enabled, the JS variables need to be set as well
		if ($this->settings['enableLightbox'] && $this->setting['insertJavaScriptForLightBox']) {

			$GLOBALS['TSFE']->getPageRenderer()->addJsFooterInlineCode('tx-mediagallery-' . $fileCollection->getIdentifier(), '
				jQuery(document).ready(function() {
					jQuery(".tx-mediagallery-lightbox-' . $fileCollection->getIdentifier() . '").colorbox({
						maxWidth: ' . intval($this->settings['single']['image']['width']) . ',
						maxHeight: ' . intval($this->settings['single']['image']['width']) . ',
						current: "' . Tx_Extbase_Utility_Localization::translate('LLL:EXT:media_gallery/Resources/Private/Language/locallang.xml:lightbox.current', 'media_gallery') . '"
					});
				});
');
		}

	}


	/**
	 * @param $contentElement
	 * @return array
	 */
	protected function getImagesFromContentElement($contentElement) {

		$imageContainer = array();
		$images = \TYPO3\CMS\Core\Utility\GeneralUtility::intExplode(',', $contentElement['image_fileUids']);
		foreach ($images as $image) {
			array_push($imageContainer, $this->fileRepository->findByUid($image));
		}

		return $imageContainer;
	}

	/**
	 * return all available image galleries for this plugin
	 *
	 * @return array of image galleries
	 */
	protected function getAvailableFileCollections() {
		$fileCollections = array();
		$limitToFileCollections = $this->settings['fileCollections'];
		if ($limitToFileCollections) {
			$fileCollectionUids = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $limitToFileCollections);
			foreach ($fileCollectionUids as $fileCollectionUid) {
				$fileCollections[] = $this->fileCollectionRepository->findByUid($fileCollectionUid);
			}
		} else {
			$fileCollections = $this->fileCollectionRepository->findAll();
		}
		return $fileCollections;
	}

}