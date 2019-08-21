<?php
namespace Standard\Sniffs\Plugins;

class CorrectPathSniff implements \PHP_CodeSniffer\Sniffs\Sniff
{
    public $isEnabled = true;

    public function register()
    {
        return [T_CLASS];
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

        $commonHelper = new \MageSuite\CodingStandard\MageSuite\Helper();
        $namespaceParts = $commonHelper->getNamespaceParts($phpcsFile, $position);

        if (empty($namespaceParts)) {
            return;
        }

        if ($namespaceParts[0] == 'Plugin') {
            $error = 'Missing relative path in plugin class';
            $phpcsFile->addWarning($error, $position, 'Found');
        }
    }


}
