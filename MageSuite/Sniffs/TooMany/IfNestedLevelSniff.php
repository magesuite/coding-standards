<?php

declare(strict_types=1);

namespace MageSuite\Sniffs\TooMany;

class IfNestedLevelSniff implements \PHP_CodeSniffer\Sniffs\Sniff
{
    public int $nestedLevelLimit = 3;

    public function register()
    {
        return [T_IF];
    }

    public function process(\PHP_CodeSniffer\Files\File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $level = --$tokens[$stackPtr]['level'];

        if ($level > $this->nestedLevelLimit) {
            $error = 'IF statement too nested (%s level, %s max)';
            $data = [$level, $this->nestedLevelLimit];

            $phpcsFile->addWarning($error, $stackPtr, 'Found', $data);

        }
    }
}
