<?php

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace TYPO3\CMS\Reports\Report\Status;

use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Reports\Status as ReportStatus;
use TYPO3\CMS\Reports\StatusProviderInterface;

/**
 * Performs basic checks about the TYPO3 install
 */
class Typo3Status implements StatusProviderInterface
{
    /**
     * Returns the status for this report
     *
     * @return array List of statuses
     */
    public function getStatus()
    {
        $statuses = [
            'registeredXclass' => $this->getRegisteredXclassStatus(),
        ];
        return $statuses;
    }

    /**
     * List any Xclasses registered in the system
     *
     * @return \TYPO3\CMS\Reports\Status
     */
    protected function getRegisteredXclassStatus()
    {
        $message = '';
        $value = $this->getLanguageService()->getLL('status_none');
        $severity = ReportStatus::OK;

        $xclassFoundArray = [];
        if (array_key_exists('Objects', $GLOBALS['TYPO3_CONF_VARS']['SYS'])) {
            foreach ($GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'] as $originalClass => $override) {
                if (array_key_exists('className', $override)) {
                    $xclassFoundArray[$originalClass] = $override['className'];
                }
            }
        }
        if (!empty($xclassFoundArray)) {
            $value = $this->getLanguageService()->getLL('status_xclassUsageFound');
            $message = $this->getLanguageService()->getLL('status_xclassUsageFound_message') . '<br />';
            $message .= '<ol>';
            foreach ($xclassFoundArray as $originalClass => $xClassName) {
                $messageDetail = sprintf(
                    (string)($this->getLanguageService()->getLL('status_xclassUsageFound_message_detail')),
                    '<code>' . htmlspecialchars((string)$originalClass) . '</code>',
                    '<code>' . htmlspecialchars((string)$xClassName) . '</code>'
                );
                $message .= '<li>' . $messageDetail . '</li>';
            }
            $message .= '</ol>';
            $severity = ReportStatus::NOTICE;
        }

        return GeneralUtility::makeInstance(
            ReportStatus::class,
            $this->getLanguageService()->getLL('status_xclassUsage'),
            $value,
            $message,
            $severity
        );
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
