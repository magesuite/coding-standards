<?php

namespace MageSuite\Helper;

class Common
{
    public function getNamespaceParts(\PHP_CodeSniffer\Files\File $phpcsFile, $stackPtr)
    {
        $namespaceDeclaration = $phpcsFile->findPrevious(T_NAMESPACE, $stackPtr);
        $endOfNamespaceDeclaration = $phpcsFile->findNext([T_SEMICOLON], $namespaceDeclaration);

        $tokens = $phpcsFile->getTokens();

        $nameParts = [];
        $currentPointer = $endOfNamespaceDeclaration - 1;

        while ($tokens[$currentPointer]['code'] === T_NS_SEPARATOR || $tokens[$currentPointer]['code'] === T_STRING
        ) {
            $nameParts[] = $tokens[$currentPointer]['content'];
            --$currentPointer;
        }

        return $nameParts;
    }
}
