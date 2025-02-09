<?php

declare(strict_types=1);

namespace MageSuite\Sniffs\Template;

class UnderscorePrefixSniff implements \PHP_CodeSniffer\Sniffs\Sniff
{
    public const UNDERSCORE_PREFIX = '$_';

    public function register()
    {
        return [T_VARIABLE];
    }

    public function process(\PHP_CodeSniffer\Files\File $phpcsFile, $stackPtr)
    {
        $token = $phpcsFile->getTokens()[$stackPtr];

        if (strpos($token['content'], self::UNDERSCORE_PREFIX) === 0) {
            $error = 'Variable %s prefixed with a single underscore';
            $phpcsFile->addWarning($error, $stackPtr, 'Found', [$token['content']]);
        }
    }
}
