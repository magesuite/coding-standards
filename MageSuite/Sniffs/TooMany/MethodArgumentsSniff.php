<?php

declare(strict_types=1);

namespace MageSuite\Sniffs\TooMany;

class MethodArgumentsSniff implements \PHP_CodeSniffer\Sniffs\Sniff
{
    public const CONSTRUCTOR_METHOD_NAME = '__construct';

    public int $argumentsLimit = 3;

    protected array $pluginPrefixes = ['before', 'after', 'around'];

    public function register()
    {
        return [T_FUNCTION];
    }

    public function process(\PHP_CodeSniffer\Files\File $phpcsFile, $stackPtr)
    {
        $methodName = $phpcsFile->getDeclarationName($stackPtr);

        if ($methodName == self::CONSTRUCTOR_METHOD_NAME) {
            return;
        }

        try {
            $parametersCount = count($phpcsFile->getMethodParameters($stackPtr));
        } catch (\PHP_CodeSniffer\Exceptions\TokenizerException $e) {
            return;
        }

        if ($parametersCount > $this->argumentsLimit) {

            if ($this->isPluginMethod($phpcsFile->getFilename(), $methodName)) {
                return;
            }

            $error = 'Too many parameters in %s() method (%s found, %s max)';
            $data = [$methodName, $parametersCount, $this->argumentsLimit];

            $phpcsFile->addWarning($error, $stackPtr, 'Found', $data);
        }
    }

    private function isPluginMethod($filePath, $methodName)
    {
        if (strpos($filePath, '/Plugin/') === false) {
            return false;
        }

        foreach ($this->pluginPrefixes as $prefix) {
            if (strpos($methodName, $prefix) === 0) {
                return true;
            }
        }

        return false;
    }
}
