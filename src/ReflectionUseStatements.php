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
     * @const string
     */
    const CLASS_STATEMENT_TYPE = 'class';

    /**
     * @const string
     */
    const ALIAS_STATEMENT_TYPE = 'alias';

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
                $rawUseStatement[self::CLASS_STATEMENT_TYPE],
                $rawUseStatement[self::ALIAS_STATEMENT_TYPE]
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
        $tokens = token_get_all($this->readFileSource());

        $useStatementIsBuilding = false;
        $statementType = '';
        $useStatements = [];
        $useStatement = [
            self::CLASS_STATEMENT_TYPE => '',
            self::ALIAS_STATEMENT_TYPE => ''
        ];

        foreach ($tokens as $token) {
            if (is_array($token)) {
                if ($token[0] === T_USE) {
                    $useStatementIsBuilding = true;

                    $statementType = self::CLASS_STATEMENT_TYPE;

                    continue;
                }

                if ($token[0] === T_AS) {
                    $statementType = self::ALIAS_STATEMENT_TYPE;

                    continue;
                }

                if ($useStatementIsBuilding && $statementType) {
                    switch ($token[0]) {
                        case T_NS_SEPARATOR:
                        case T_STRING:
                            $useStatement[$statementType] .= $token[1];

                            break;

                    }
                }
            } else {
                if (($token === ';' || $token === ',') && $useStatementIsBuilding) {
                    $useStatements[] = $useStatement;

                    $useStatement = [
                        self::CLASS_STATEMENT_TYPE => '',
                        self::ALIAS_STATEMENT_TYPE => ''
                    ];

                    if ($token === ';') {
                        $useStatementIsBuilding = false;
                    }

                    if ($token === ',') {
                        $statementType = self::CLASS_STATEMENT_TYPE;
                    }
                }
            }
        }

        return $useStatements;
    }

}