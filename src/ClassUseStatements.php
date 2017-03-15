<?php

declare(strict_types = 1);

namespace Reflection;

use \ReflectionClass;
use \RuntimeException;

use \Reflection\ClassUseStatements\UseStatements;
use \Reflection\ClassUseStatements\Parser;

/**
 * Class ClassUseStatements
 * @package UsesReflection
 */
class ClassUseStatements extends ReflectionClass {

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
     * @param string $statement
     * @return boolean
     */
    public function hasUseStatement(string $statement): bool {
        return $this->getUseStatements()
            ->hasStatement($statement);
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
     * @return ClassUseStatements
     */
    private function setUseStatementsIsParsed(): ClassUseStatements {
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

        return (new Parser($this->readUsesBlock()))
            ->getUseStatements();
    }

    /**
     * @return string
     */
    private function readUsesBlock(): string {
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

}