<?php
namespace Standard\Sniffs\Plugins;

class UnderscorePrefixSniff implements \PHP_CodeSniffer\Sniffs\Sniff
{
    const UNDERSCORE_PREFIX = '$_';

    public $isEnabled = true;

    public function register()
    {
        return [T_VARIABLE];
    }

    public function process(\PHP_CodeSniffer\Files\File $phpcsFile, $position)
    {
        if (!$this->isEnabled) {
            return;
        }

        $token = $phpcsFile->getTokens()[$position];

        if (strpos($token['content'], self::UNDERSCORE_PREFIX) === 0) {
            $error = 'Variable %s prefixed with a single underscore';
            $phpcsFile->addWarning($error, $position, 'Found', [$token['content']]);
        }

    }


}
