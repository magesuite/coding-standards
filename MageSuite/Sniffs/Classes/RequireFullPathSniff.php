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

        $tokens = $phpcsFile->getTokens();
        $tokenInformation = $tokens[$position];

        $classNameTokenKey = array_search('T_CLASS', array_column($tokens, 'type'));
        $classNameLine = $tokens[$classNameTokenKey]['line'];

        if($classNameLine > $tokenInformation['line']) {
            $phpcsFile->addWarning('Don\'t import class, use fully qualified class name', $position, 'Use_Used');
        }

    }
}
