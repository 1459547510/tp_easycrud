<?php

namespace Easycrud;

abstract class Create
{
    protected ?string $parame;
    protected ?string $namespaceName;
    protected ?string $className;

    /**
     * @var bool $initializedStatus
     * 初始化状态
     */
    protected bool $initializedStatus;

    public function __construct(string $parame)
    {
        $this->parame = $parame;
        $this->initializedStatus = false;
    }

    public function getStub(string $name)
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . "{$name}.stub";
    }

    public function __get($name)
    {
        if ($this->isTypedInitialized($name)) {
            return $this->$name;
        } else {
            return null;
        }
    }

    /**
     * 是否初始化属性
     * @param string $name
     * @return bool
     */
    protected function isTypedInitialized(string $name): bool
    {
        return (new \ReflectionClass(self::class))
            ->getProperty($name)
            ->isInitialized($this);
    }
}
