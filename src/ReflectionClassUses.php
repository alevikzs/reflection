<?php

declare(strict_types = 1);

namespace UsesReflection;

use \ReflectionClass;
use \RuntimeException;

/**
 * Class ReflectionClassUses
 * @package UsesReflection
 */
class ReflectionClassUses extends ReflectionClass {

    /**
     * @var array
     */
    private $useStatements = [];

    /**
     * @var boolean
     */
    private $isUseStatementsParsed = false;

    /**
     * @return array
     */
    public function getUseStatements(): array {
        if ($this->isUseStatementsNotParsed()) {
            $this->useStatements = $this->parseUseStatements();
        }

        return $this->useStatements;
    }

    /**
     * @param string $class
     * @return boolean
     */
    public function hasUseStatement(string $class): bool {
        $useStatements = $this->getUseStatements();

        return
            array_search($class, array_column($useStatements, 'class')) ||
            array_search($class, array_column($useStatements, 'as'));
    }

    /**
     * @return bool
     */
    public function isNotUserDefined(): bool {
        return !$this->isUserDefined();
    }

    /**
     * @return boolean
     */
    private function isUseStatementsNotParsed(): bool {
        return !$this->isUseStatementsParsed;
    }

    /**
     * @return ReflectionClassUses
     */
    private function setUseStatementsIsParsed(): ReflectionClassUses {
        $this->isUseStatementsParsed = true;

        return $this;
    }

    /**
     * @return array
     */
    private function parseUseStatements(): array {
        if ($this->isNotUserDefined()) {
            throw new RuntimeException('Can get use statements from user defined classes only.');
        }

        return $this->setUseStatementsIsParsed()
            ->tokenizeSource();
    }

    /**
     * @return string
     */
    private function readFileSource(): string {
        $file = fopen($this->getFileName(), 'r');
        $line = 0;
        $source = '';

        while (!feof($file)) {
            ++$line;

            if ($line >= $this->getStartLine()) {
                break;
            }

            $source .= fgets($file);
        }

        fclose($file);

        return $source;
    }

    /**
     * @return array
     */
    private function tokenizeSource(): array {
        $tokens = token_get_all($this->readFileSource());
        $builtNamespace = '';
        $buildingNamespace = false;
        $matchedNamespace = false;
        $useStatements = [];
        $record = false;
        $currentUse = [
            'class' => '',
            'as' => ''
        ];

        foreach ($tokens as $token) {
            if ($token[0] === T_NAMESPACE) {
                $buildingNamespace = true;

                if ($matchedNamespace) {
                    break;
                }
            }

            if ($buildingNamespace) {
                if ($token === ';') {
                    $buildingNamespace = false;

                    continue;
                }

                switch ($token[0]) {
                    case T_STRING:
                    case T_NS_SEPARATOR:
                        $builtNamespace .= $token[1];

                        break;
                }

                continue;
            }

            if ($token === ';' || !is_array($token)) {
                if ($record) {
                    $useStatements[] = $currentUse;
                    $record = false;
                    $currentUse = [
                        'class' => '',
                        'as' => ''
                    ];
                }

                continue;
            }

            if ($token[0] === T_CLASS) {
                break;
            }

            if (strcasecmp($builtNamespace, $this->getNamespaceName()) === 0) {
                $matchedNamespace = true;
            }

            if ($matchedNamespace) {
                if ($token[0] === T_USE) {
                    $record = 'class';
                }

                if ($token[0] === T_AS) {
                    $record = 'as';
                }

                if ($record) {
                    switch ($token[0]) {
                        case T_STRING:
                        case T_NS_SEPARATOR:
                            if ($record) {
                                $currentUse[$record] .= $token[1];
                            }

                            break;
                    }
                }
            }

            if ($token[2] >= $this->getStartLine()) {
                break;
            }
        }

        foreach ($useStatements as &$useStatement) {
            if (empty($useStatement['as'])) {

                $useStatement['as'] = basename($useStatement['class']);
            }
        }

        return $useStatements;
    }

}