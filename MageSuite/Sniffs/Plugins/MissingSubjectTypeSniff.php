<?php
namespace Standard\Sniffs\Plugins;

class MissingSubjectTypeSniff implements \PHP_CodeSniffer\Sniffs\Sniff
{
    public $isEnabled = true;

    protected $pluginMethodPrefixes = ['before', 'after', 'around'];

    public function register()
    {
        return [T_FUNCTION];
    }

    public function process(\PHP_CodeSniffer\Files\File $phpcsFile, $position)
    {
        if (!$this->isEnabled) {
            return;
        }

        $filePath = $phpcsFile->getFilename();

        if (strpos($filePath, '/Plugin/') === false) {
            return;
        }

        $methodName = $phpcsFile->getDeclarationName($position);

        $pluginMethod = false;
        foreach ($this->pluginMethodPrefixes as $pluginMethodPrefix) {
            if (strpos($methodName, $pluginMethodPrefix) !== 0) {
                continue;
            }

            $pluginMethod = true;
        }

        if (!$pluginMethod) {
            return;
        }

        $parameters = $phpcsFile->getMethodParameters($position);

        if (empty($parameters)) {
            return;
        }

        if (empty($parameters[0]['type_hint'])) {
            $error = 'Missing type for %s argument';
            $phpcsFile->addWarning($error, $position, 'Found', [$parameters[0]['name']]);
        }


    }


}
