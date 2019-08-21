<?php

namespace Standard\Sniffs\TooMany;

class MethodArgumentsSniff implements \PHP_CodeSniffer\Sniffs\Sniff
{
    public $isEnabled = true;

    public $argumentsLimit = 3;

    public function register()
    {
        return [T_FUNCTION];
    }

    public function process(\PHP_CodeSniffer\Files\File $phpcsFile, $position)
    {
        if (!$this->isEnabled) {
            return;
        }

        $parametersCount = count($phpcsFile->getMethodParameters($position));

        if ($parametersCount > $this->argumentsLimit) {
            $error = 'Too many parameters in %s() method (%s found, %s max)';
            $data = [$phpcsFile->getDeclarationName($position), $parametersCount, $this->argumentsLimit];

            $phpcsFile->addWarning($error, $position, 'Found', $data);
        }
    }
}
