<?php

namespace Standard\Sniffs\TooMany;

use PHP_CodeSniffer\Exceptions\TokenizerException;

class MethodArgumentsSniff implements \PHP_CodeSniffer\Sniffs\Sniff
{
    const CONSTRUCTOR_METHOD_NAME = '__construct';

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

        $methodName = $phpcsFile->getDeclarationName($position);

        if ($methodName == self::CONSTRUCTOR_METHOD_NAME) {
            return;
        }

        try {
            $parametersCount = count($phpcsFile->getMethodParameters($position));
        } catch (TokenizerException $e) {
            return;
        }

        if ($parametersCount > $this->argumentsLimit) {
            $error = 'Too many parameters in %s() method (%s found, %s max)';
            $data = [$methodName, $parametersCount, $this->argumentsLimit];

            $phpcsFile->addWarning($error, $position, 'Found', $data);
        }
    }
}
