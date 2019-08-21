<?php

namespace Standard\Sniffs\TooMany;

class IfConditionsSniff implements \PHP_CodeSniffer\Sniffs\Sniff
{
    public $isEnabled = true;

    public $conditionsLimit = 3;

    protected $types = ['T_BOOLEAN_AND', 'T_BOOLEAN_OR', 'T_LOGICAL_AND', 'T_LOGICAL_OR', 'T_LOGICAL_XOR'];

    public function register()
    {
        return [T_IF, T_ELSEIF];
    }

    public function process(\PHP_CodeSniffer\Files\File $phpcsFile, $position)
    {
        if (!$this->isEnabled) {
            return;
        }

        $tokens = $phpcsFile->getTokens();
        $token = $tokens[$position];

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

            $phpcsFile->addWarning($error, $position, 'Found', $data);
        }
    }
}
