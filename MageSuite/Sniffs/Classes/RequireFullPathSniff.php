<?php

declare(strict_types=1);

namespace MageSuite\Sniffs\Classes;

class RequireFullPathSniff implements \PHP_CodeSniffer\Sniffs\Sniff
{
    public function register()
    {
        return [T_USE];
    }

    public function process(\PHP_CodeSniffer\Files\File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $tokenInformation = $tokens[$stackPtr];

        $classNameTokenKey = array_search('T_CLASS', array_column($tokens, 'type'));
        $classNameLine = $tokens[$classNameTokenKey]['line'];

        if($classNameLine > $tokenInformation['line']) {
            $phpcsFile->addWarning('Don\'t import class, use fully qualified class name', $stackPtr, 'Use_Used');
        }

    }
}
