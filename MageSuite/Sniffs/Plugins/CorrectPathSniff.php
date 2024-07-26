<?php

declare(strict_types=1);

namespace MageSuite\Sniffs\Plugins;

class CorrectPathSniff implements \PHP_CodeSniffer\Sniffs\Sniff
{
    public function register()
    {
        return [T_CLASS];
    }

    public function process(\PHP_CodeSniffer\Files\File $phpcsFile, $stackPtr)
    {
        $filePath = $phpcsFile->getFilename();

        if (strpos($filePath, '/Plugin/') === false) {
            return;
        }

        $commonHelper = new \MageSuite\Helper\Common();
        $namespaceParts = $commonHelper->getNamespaceParts($phpcsFile, $stackPtr);

        if (empty($namespaceParts)) {
            return;
        }

        if ($namespaceParts[0] == 'Plugin') {
            $error = 'Missing relative path in plugin class';
            $phpcsFile->addWarning($error, $stackPtr, 'Found');
        }
    }


}
