<?php

declare(strict_types = 1);

namespace Reflection\ClassUseStatements;

/**
 * Class UsesBlockParser
 * @package Reflection
 */
class UsesBlockParser {

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
     * @var boolean
     */
    private $isUseStatementBuilding = false;

    /**
     * @var string
     */
    private $currentUseStatement = '';

    /**
     * @var string
     */
    private $committedUseStatement = '';

    /**
     * @var string
     */
    private $currentAliasStatement = '';

    /**
     * @var string
     */
    private $currentStatementType = '';

    /**
     * @var bool
     */
    private $isBrace = false;

    /**
     * UsesBlockParser constructor.
     * @param string $usesBlock
     */
    public function __construct(string $usesBlock) {
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
     * @return UsesBlockParser
     */
    public function setUsesBlock(string $usesBlock): UsesBlockParser {
        $this->usesBlock = $usesBlock;

        return $this;
    }

    /**
     * @return boolean
     */
    protected function isUseStatementBuilding(): bool {
        return $this->isUseStatementBuilding;
    }

    /**
     * @return UsesBlockParser
     */
    protected function setUseStatementIsBuilding(): UsesBlockParser {
        $this->isUseStatementBuilding = true;
        
        return $this;
    }

    /**
     * @return UsesBlockParser
     */
    protected function setUseStatementIsNotBuilding(): UsesBlockParser {
        $this->isUseStatementBuilding = false;

        return $this;
    }

    /**
     * @return string
     */
    protected function getCurrentUseStatement(): string {
        if ($this->isBrace()) {
            return $this->getCommittedUseStatement() . $this->currentUseStatement;
        }

        return $this->currentUseStatement;
    }

    /**
     * @param string $statement
     * @return UsesBlockParser
     */
    protected function setCurrentUseStatement(string $statement): UsesBlockParser {
        $this->currentUseStatement .= $statement;
        
        return $this;
    }

    /**
     * @return UsesBlockParser
     */
    protected function clearCurrentUseStatement(): UsesBlockParser {
        $this->currentUseStatement = '';

        return $this;
    }

    /**
     * @return string
     */
    protected function getCommittedUseStatement(): string {
        return $this->committedUseStatement;
    }

    /**
     * @return UsesBlockParser
     */
    protected function commitUseStatement(): UsesBlockParser {
        $this->committedUseStatement = $this->currentUseStatement;

        return $this;
    }

    /**
     * @return string
     */
    protected function getCurrentAliasStatement(): string {
        return $this->currentAliasStatement;
    }

    /**
     * @param string $statement
     * @return UsesBlockParser
     */
    protected function setCurrentAliasStatement(string $statement): UsesBlockParser {
        $this->currentAliasStatement = $statement;
        
        return $this;
    }

    /**
     * @return string
     */
    protected function getCurrentStatementType(): string {
        return $this->currentStatementType;
    }

    /**
     * @param string $currentStatementType
     * @return UsesBlockParser
     */
    protected function setCurrentStatementType(string $currentStatementType): UsesBlockParser {
        $this->currentStatementType = $currentStatementType;
        
        return $this;
    }

    /**
     * @return UsesBlockParser
     */
    protected function setIsBrace(): UsesBlockParser {
        $this->isBrace = true;

        return $this->commitUseStatement()
            ->clearCurrentUseStatement();
    }

    /**
     * @return UsesBlockParser
     */
    protected function setIsNotBrace(): UsesBlockParser {
        $this->isBrace = false;
        $this->committedUseStatement = '';

        return $this;
    }

    /**
     * @return bool
     */
    protected function isBrace(): bool {
        return $this->isBrace;
    }
    
    /**
     * @return UseStatements
     */
    public function getUseStatements(): UseStatements {
        $useStatements = new UseStatements();
        
        foreach ($this->getTokens() as $token) {
            if (is_array($token)) {
                if ($token[0] === T_USE) {
                    $this->setUseStatementIsBuilding()
                        ->setCurrentStatementType(self::USE_STATEMENT_TYPE);

                    continue;
                }

                if ($token[0] === T_AS) {
                    $this->setCurrentStatementType(self::ALIAS_STATEMENT_TYPE);

                    continue;
                }

                if ($this->isUseStatementBuilding() && $this->getCurrentStatementType()) {
                    switch ($token[0]) {
                        case T_NS_SEPARATOR:
                        case T_STRING:
                            $this->setCurrentStatement($token[1]);

                            break;
                    }
                }
            } else {
                if (($token === ';' || $token === ',') && $this->isUseStatementBuilding()) {
                    $useStatements->add(new UseStatement(
                        $this->getCurrentUseStatement(),
                        $this->getCurrentAliasStatement()
                    ));

                    $this->clearCurrentStatements();

                    if ($token === ';') {
                        $this->setUseStatementIsNotBuilding();
                    }

                    if ($token === ',') {
                        $this->setCurrentStatementType(self::USE_STATEMENT_TYPE);
                    }
                }

                if ($token === '{') {
                    $this->setIsBrace();
                }

                if ($token === ';') {
                    $this->setIsNotBrace();
                }
            }
        }

        return $useStatements;
    }
    
    /**
     * @return array
     */
    private function getTokens(): array {
        return token_get_all($this->getUsesBlock());
    }

    /**
     * @param string $value
     * @return UsesBlockParser
     */
    private function setCurrentStatement(string $value): UsesBlockParser {
        $type = $this->getCurrentStatementType();

        $setter = 'setCurrent' . ucfirst($type) . 'Statement';

        return $this->$setter($value);
    }

    /**
     * @return UsesBlockParser
     */
    protected function clearCurrentStatements(): UsesBlockParser {
        return $this->clearCurrentUseStatement()
            ->setCurrentAliasStatement('');
    }
    
}