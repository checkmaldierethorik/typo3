<?php

declare(strict_types=1);

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

namespace TYPO3\CMS\SysNote\Hook;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\SysNote\Renderer\NoteRenderer;

/**
 * Hook for the info module.
 *
 * @internal This is a specific hook implementation and is not considered part of the Public TYPO3 API.
 */
class InfoModuleHook
{
    public function __construct(protected readonly NoteRenderer $noteRenderer)
    {
    }

    /**
     * Add sys_notes as additional content to the footer of the info module
     */
    public function render(array $params): string
    {
        /** @var ServerRequestInterface $request */
        $request = $params['request'];
        $id = (int)($request->getQueryParams()['id'] ?? 0);
        $returnUrl = $request->getAttribute('normalizedParams')->getRequestUri();
        return $this->noteRenderer->renderList($request, $id, null, $returnUrl);
    }
}
