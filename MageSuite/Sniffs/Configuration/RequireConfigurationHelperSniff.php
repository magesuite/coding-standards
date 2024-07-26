<?php

declare(strict_types=1);

namespace MageSuite\Sniffs\Configuration;

class RequireConfigurationHelperSniff implements \PHP_CodeSniffer\Sniffs\Sniff
{
    public const CONSTRUCTOR_METHOD_NAME = '__construct';
    public const ACCEPTED_CLASS_NAMES = ['Configuration', 'Config'];

    public function register()
    {
        return [T_FUNCTION];
    }

    public function process(\PHP_CodeSniffer\Files\File $phpcsFile, $stackPtr)
    {
        if ($phpcsFile->getDeclarationName($stackPtr) != self::CONSTRUCTOR_METHOD_NAME) {
            return;
        }

        $methodParameters = $phpcsFile->getMethodParameters($stackPtr);
        $configClassFound = array_search('\Magento\Framework\App\Config\ScopeConfigInterface', array_column($methodParameters, 'type_hint'));

        if (!$configClassFound) {
            return;
        }

        $commonHelper = new \MageSuite\Helper\Common();
        $namespaceParts = $commonHelper->getNamespaceParts($phpcsFile, $stackPtr);

        $isAcceptedClassInNamespace = (bool)count(array_intersect(self::ACCEPTED_CLASS_NAMES, $namespaceParts));

        if ($isAcceptedClassInNamespace) {
            return;
        }

        $classPosition = $phpcsFile->findPrevious(T_CLASS, $stackPtr);
        $className = $phpcsFile->getDeclarationName($classPosition);

        if (in_array($className, self::ACCEPTED_CLASS_NAMES)) {
            return;
        }

        $error = 'ScopeConfigInterface class should be used in separate configuration helper only';
        $phpcsFile->addWarning($error, $stackPtr, 'Found');
    }
}
