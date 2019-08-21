<?php

namespace Standard\Sniffs\TooMany;

class IfNestedLevelSniff implements \PHP_CodeSniffer\Sniffs\Sniff
{
    public $isEnabled = true;

    public $nestedLevelLimit = 3;

    public function register()
    {
        return [T_IF];
    }

    public function process(\PHP_CodeSniffer\Files\File $phpcsFile, $position)
    {
        if (!$this->isEnabled) {
            return;
        }

        $tokens = $phpcsFile->getTokens();

        $level = --$tokens[$position]['level'];

        if ($level > $this->nestedLevelLimit) {
            $error = 'IF statement too nested (%s level, %s max)';
            $data = [$level, $this->nestedLevelLimit];

            $phpcsFile->addWarning($error, $position, 'Found', $data);

        }
    }
}
