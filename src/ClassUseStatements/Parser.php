<?php

declare(strict_types = 1);

namespace Reflection\ClassUseStatements;

/**
 * Class Parser
 * @package Reflection
 */
class Parser {

    /**
     * @const string
     */
    const USE_STATEMENT_TYPE = 'use';

    /**
     * @const string
     */
    const ALIAS_STATEMENT_TYPE = 'alias';

    /**
     * @var string
     */
    private $usesBlock;

    /**
     * @var UseStatements
     */
    private $useStatements;

    /**
     * @var boolean
     */
    private $isUseStatementBuilding = false;

    /**
     * @var string
     */
    private $useStatement = '';

    /**
     * @var string
     */
    private $committedUseStatement = '';

    /**
     * @var string
     */
    private $aliasStatement = '';

    /**
     * @var string
     */
    private $statementType = '';

    /**
     * @var bool
     */
    private $isBrace = false;

    /**
     * Parser constructor.
     * @param string $usesBlock
     */
    public function __construct(string $usesBlock) {
        $this->useStatements = new UseStatements();

        $this->setUsesBlock($usesBlock);
    }

    /**
     * @return string
     */
    public function getUsesBlock(): string {
        return $this->usesBlock;
    }

    /**
     * @param string $usesBlock
     * @return Parser
     */
    public function setUsesBlock(string $usesBlock): Parser {
        $this->usesBlock = $usesBlock;

        return $this;
    }

    /**
     * @param UseStatement $useStatement
     * @return Parser
     */
    private function addUseStatement(UseStatement $useStatement): Parser {
        $this->useStatements->add($useStatement);

        return $this;
    }

    /**
     * @return boolean
     */
    private function isUseStatementBuilding(): bool {
        return $this->isUseStatementBuilding;
    }

    /**
     * @return Parser
     */
    private function setUseStatementIsBuilding(): Parser {
        $this->isUseStatementBuilding = true;
        
        return $this;
    }

    /**
     * @return Parser
     */
    private function setUseStatementIsNotBuilding(): Parser {
        $this->isUseStatementBuilding = false;

        return $this;
    }

    /**
     * @return string
     */
    private function getUseStatement(): string {
        if ($this->isBrace()) {
            return $this->getCommittedUseStatement() . $this->useStatement;
        }

        return $this->useStatement;
    }

    /**
     * @param string $statement
     * @return Parser
     */
    private function setUseStatement(string $statement): Parser {
        $this->useStatement .= $statement;
        
        return $this;
    }

    /**
     * @return Parser
     */
    private function clearUseStatement(): Parser {
        $this->useStatement = '';

        return $this;
    }

    /**
     * @return string
     */
    private function getCommittedUseStatement(): string {
        return $this->committedUseStatement;
    }

    /**
     * @return Parser
     */
    private function commitUseStatement(): Parser {
        $this->committedUseStatement = $this->useStatement;

        return $this;
    }

    /**
     * @return string
     */
    private function getAliasStatement(): string {
        return $this->aliasStatement;
    }

    /**
     * @param string $statement
     * @return Parser
     */
    private function setAliasStatement(string $statement): Parser {
        $this->aliasStatement = $statement;
        
        return $this;
    }

    /**
     * @return string
     */
    private function getStatementType(): string {
        return $this->statementType;
    }

    /**
     * @param string $statementType
     * @return Parser
     */
    private function setStatementType(string $statementType): Parser {
        $this->statementType = $statementType;
        
        return $this;
    }

    /**
     * @return Parser
     */
    private function setIsBrace(): Parser {
        $this->isBrace = true;

        return $this->commitUseStatement()
            ->clearUseStatement();
    }

    /**
     * @return Parser
     */
    private function setIsNotBrace(): Parser {
        $this->isBrace = false;
        $this->committedUseStatement = '';

        return $this;
    }

    /**
     * @return bool
     */
    private function isBrace(): bool {
        return $this->isBrace;
    }
    
    /**
     * @return UseStatements
     */
    public function getUseStatements(): UseStatements {
        foreach ($this->getTokens() as $token) {
            if (is_array($token)) {
                $this->analyzeStatementToken($token);
            } else {
                $this->analyzeDelimiterToken($token);
            }
        }

        return $this->useStatements;
    }
    
    /**
     * @return array
     */
    private function getTokens(): array {
        return token_get_all($this->getUsesBlock());
    }

    /**
     * @uses \Reflection\ClassUseStatements\Parser::setUseStatement()
     * @uses \Reflection\ClassUseStatements\Parser::setAliasStatement()
     * @param string $value
     * @return Parser
     */
    private function setStatement(string $value): Parser {
        $type = $this->getStatementType();

        $setter = 'set' . ucfirst($type) . 'Statement';

        return $this->$setter($value);
    }

    /**
     * @return Parser
     */
    private function clearStatements(): Parser {
        return $this->clearUseStatement()
            ->setAliasStatement('');
    }

    /**
     * @param string $token
     * @return Parser
     */
    private function analyzeDelimiterToken(string $token): Parser {
        if (($token === ';' || $token === ',') && $this->isUseStatementBuilding()) {
            $this->addUseStatement(new UseStatement(
                $this->getUseStatement(),
                $this->getAliasStatement()
            ));

            $this->clearStatements();

            if ($token === ';') {
                $this->setUseStatementIsNotBuilding();
            }

            if ($token === ',') {
                $this->setStatementType(self::USE_STATEMENT_TYPE);
            }
        }

        if ($token === '{') {
            $this->setIsBrace();
        }

        if ($token === ';') {
            $this->setIsNotBrace();
        }

        return $this;
    }

    /**
     * @param array $token
     * @return Parser
     */
    private function analyzeStatementToken(array $token): Parser {
        if ($token[0] === T_USE) {
            $this->setUseStatementIsBuilding()
                ->setStatementType(self::USE_STATEMENT_TYPE);
        } elseif ($token[0] === T_AS) {
            $this->setStatementType(self::ALIAS_STATEMENT_TYPE);
        } elseif ($this->isUseStatementBuilding() && $this->getStatementType()) {
            switch ($token[0]) {
                case T_NS_SEPARATOR:
                case T_STRING:
                    $this->setStatement($token[1]);

                    break;
            }
        }

        return $this;
    }
    
}