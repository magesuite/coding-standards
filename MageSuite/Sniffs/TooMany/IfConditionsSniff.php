<?php

declare(strict_types=1);

namespace MageSuite\Sniffs\TooMany;

class IfConditionsSniff implements \PHP_CodeSniffer\Sniffs\Sniff
{
    public int $conditionsLimit = 3;

    protected array $types = ['T_BOOLEAN_AND', 'T_BOOLEAN_OR', 'T_LOGICAL_AND', 'T_LOGICAL_OR', 'T_LOGICAL_XOR'];

    public function register()
    {
        return [T_IF, T_ELSEIF];
    }

    public function process(\PHP_CodeSniffer\Files\File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $token = $tokens[$stackPtr];

        if (!isset($token['parenthesis_opener'])) {
            return;
        }

        $next = ++$token['parenthesis_opener'];
        $end = --$token['parenthesis_closer'];

        $types = array_flip($this->types);

        $count = 1;

        for (; $next <= $end; ++$next) {
            $type = $tokens[$next]['type'];

            if (!isset($types[$type])) {
                continue;
            }

            $count++;
        }

        if ($count > $this->conditionsLimit) {
            $error = 'Too many conditions in IF statement (%s found, %s max)';
            $data = [$count, $this->conditionsLimit];

            $phpcsFile->addWarning($error, $stackPtr, 'Found', $data);
        }
    }
}
