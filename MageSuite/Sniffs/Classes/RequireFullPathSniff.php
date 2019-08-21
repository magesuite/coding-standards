<?php
namespace Standard\Sniffs\Classes;

class RequireFullPathSniff implements \PHP_CodeSniffer\Sniffs\Sniff
{
    public $isEnabled = true;

    public function register()
    {
        return [T_USE];
    }

    public function process(\PHP_CodeSniffer\Files\File $phpcsFile, $position)
    {
        if (!$this->isEnabled) {
            return;
        }

        $phpcsFile->addWarning('Don\'t import class, use fully qualified class name', $position, 'Use_Used');
    }
}
