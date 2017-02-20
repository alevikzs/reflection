<?php

declare(strict_types = 1);

namespace Reflection;

use \ReflectionClass;
use \RuntimeException;

/**
 * Class ReflectionUseStatements
 * @package UsesReflection
 */
class ReflectionUseStatements extends ReflectionClass {

    /**
     * @var UseStatements
     */
    private $useStatements;

    /**
     * @var boolean
     */
    private $isUseStatementsParsed = false;

    /**
     * @return UseStatements
     */
    public function getUseStatements(): UseStatements {
        if ($this->isUseStatementsNotParsed()) {
            $this->useStatements = $this->createUseStatements();
        }

        return $this->useStatements;
    }

    /**
     * @param string $class
     * @return boolean
     */
    public function hasUseStatement(string $class): bool {
        return $this->getUseStatements()
            ->hasClass($class);
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
     * @return ReflectionUseStatements
     */
    private function setUseStatementsIsParsed(): ReflectionUseStatements {
        $this->isUseStatementsParsed = true;

        return $this;
    }

    /**
     * @return UseStatements
     */
    private function createUseStatements(): UseStatements {
        if ($this->isNotUserDefined()) {
            throw new RuntimeException('Can get use statements from user defined classes only.');
        }

        $this->setUseStatementsIsParsed();

        $rawUseStatements = $this->createRawUseStatements();

        $useStatements = new UseStatements();

        foreach ($rawUseStatements as $rawUseStatement) {
            $useStatements->add(new UseStatement(
                $rawUseStatement['class'],
                $rawUseStatement['alias']
            ));
        }

        return $useStatements;
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
    private function createRawUseStatements(): array {
        $builtNamespace = '';
        $tokenType = '';

        $buildingNamespace = false;
        $matchedNamespace = false;

        $rawUseStatements = [];
        $rawUseStatement = [
            'class' => '',
            'alias' => ''
        ];

        $tokens = token_get_all($this->readFileSource());

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
                if ($tokenType) {
                    $rawUseStatements[] = $rawUseStatement;
                    $rawUseStatement = [
                        'class' => '',
                        'alias' => ''
                    ];

                    $tokenType = '';
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
                    $tokenType = 'class';
                }

                if ($token[0] === T_AS) {
                    $tokenType = 'alias';
                }

                if ($tokenType) {
                    switch ($token[0]) {
                        case T_STRING:
                        case T_NS_SEPARATOR:
                            if ($tokenType) {
                                $rawUseStatement[$tokenType] .= $token[1];
                            }

                            break;
                    }
                }
            }

            if ($token[2] >= $this->getStartLine()) {
                break;
            }
        }

        return $rawUseStatements;
    }

}