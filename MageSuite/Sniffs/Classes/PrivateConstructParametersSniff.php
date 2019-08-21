<?php
namespace Standard\Sniffs\Classes;

class PrivateConstructParametersSniff implements \PHP_CodeSniffer\Sniffs\Sniff
{
    const CONSTRUCTOR_METHOD_NAME = '__construct';

    public $isEnabled = true;

    public function register()
    {
        return [T_FUNCTION];
    }

    public function process(\PHP_CodeSniffer\Files\File $phpcsFile, $position)
    {
        if (!$this->isEnabled) {
            return;
        }

        if ($phpcsFile->getDeclarationName($position) != self::CONSTRUCTOR_METHOD_NAME) {
            return;
        }

        $next = $phpcsFile->findPrevious([T_OPEN_CURLY_BRACKET], $position);
        $end = $position;

        $tokens = $phpcsFile->getTokens();

        $error = 'Don\'t use private scope in constructor arguments';

        for (; $next <= $end; ++$next) {
            if ($tokens[$next]['type'] != 'T_PRIVATE') {
                continue;
            }

            $phpcsFile->addWarning($error, $next, 'Found');
        }
    }


}
