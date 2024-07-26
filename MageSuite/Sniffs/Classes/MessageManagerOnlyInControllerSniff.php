<?php

declare(strict_types=1);

namespace MageSuite\Sniffs\Classes;

class MessageManagerOnlyInControllerSniff implements \PHP_CodeSniffer\Sniffs\Sniff
{
    public const CONSTRUCTOR_METHOD_NAME = '__construct';

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
        $messageManagerFound = array_search('\Magento\Framework\Message\ManagerInterface', array_column($methodParameters, 'type_hint'));

        if (!$messageManagerFound) {
            return;
        }

        $commonHelper = new \MageSuite\Helper\Common();
        $namespaceParts = $commonHelper->getNamespaceParts($phpcsFile, $stackPtr);

        if (in_array('Controller', $namespaceParts)) {
            return;
        }

        $phpcsFile->addWarning('ManagerInterface should be used only in the controller', $stackPtr, 'Found');
    }
}
