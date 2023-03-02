<?php
namespace Standard\Sniffs\Configuration;

class RequireConfigurationHelperSniff implements \PHP_CodeSniffer\Sniffs\Sniff
{
    const CONSTRUCTOR_METHOD_NAME = '__construct';
    const ACCEPTED_CLASS_NAMES = ['Configuration', 'Config'];

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

        $configClassFound = false;

        foreach ($phpcsFile->getMethodParameters($position) as $methodParameter) {
            if ($methodParameter['type_hint'] != '\Magento\Framework\App\Config\ScopeConfigInterface') {
                continue;
            }

            $configClassFound = true;
        }

        if (!$configClassFound) {
            return;
        }

        $commonHelper = new \MageSuite\Helper\Common();
        $namespaceParts = $commonHelper->getNamespaceParts($phpcsFile, $position);

        $isAcceptedClassInNamespace = (bool)count(array_intersect(self::ACCEPTED_CLASS_NAMES, $namespaceParts));

        if ($isAcceptedClassInNamespace) {
            return;
        }

        $classPosition = $phpcsFile->findPrevious(T_CLASS, $position);
        $className = $phpcsFile->getDeclarationName($classPosition);

        if (in_array($className, self::ACCEPTED_CLASS_NAMES)) {
            return;
        }

        $error = 'ScopeConfigInterface class should be used in separate configuration helper only';
        $phpcsFile->addWarning($error, $position, 'Found');
    }
}
