<?php

declare(strict_types=1);

namespace MageSuite\Sniffs\Plugins;

class MissingSubjectTypeSniff implements \PHP_CodeSniffer\Sniffs\Sniff
{
    protected array $pluginMethodPrefixes = ['before', 'after', 'around'];

    public function register()
    {
        return [T_FUNCTION];
    }

    public function process(\PHP_CodeSniffer\Files\File $phpcsFile, $stackPtr)
    {
        $filePath = $phpcsFile->getFilename();

        if (strpos($filePath, '/Plugin/') === false) {
            return;
        }

        $methodName = $phpcsFile->getDeclarationName($stackPtr);

        $pluginMethod = false;
        foreach ($this->pluginMethodPrefixes as $pluginMethodPrefix) {
            if (strpos($methodName, $pluginMethodPrefix) !== 0) {
                continue;
            }

            $pluginMethod = true;
        }

        if (!$pluginMethod) {
            return;
        }

        $parameters = $phpcsFile->getMethodParameters($stackPtr);

        if (empty($parameters)) {
            return;
        }

        if (empty($parameters[0]['type_hint'])) {
            $error = 'Missing type for %s argument';
            $phpcsFile->addWarning($error, $stackPtr, 'Found', [$parameters[0]['name']]);
        }


    }


}
