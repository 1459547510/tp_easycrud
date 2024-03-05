<?php

namespace Easycrud\Create;

use Easycrud\Create;

class Model extends Create
{
    public function __construct(string $parame)
    {
        parent::__construct($parame);
        $fm = str_replace("/", "\\", explode(':', $parame)[1] ?? '');
        if ($fm == 'null') {
            $this->initializedStatus = false;
        } else {
            $fm = explode('\\', $fm);
            $this->className =  end($fm);
            unset($fm[array_key_last($fm)]);
            $this->namespaceName = implode("\\", $fm);
            $this->initializedStatus = true;
        }
    }

    public function run(): int|false
    {
        try {
            if ($this->initializedStatus === false) {
                return false;
            }
            // 获取需要写入的数据
            $controllerData = file_get_contents($this->getStub('model'));
            $className = $this->className;
            $namesPace = $this->namespaceName;
            // 文件生成路径
            $path = './app/' . str_replace("\\", '/', $namesPace);
            $tableName = '';
            $resData = str_replace([
                '{%className%}', '{%namespace%}'
            ], [$className, $namesPace], $controllerData);
            if (!is_dir($path)) {
                mkdir($path, 0775, true);
            }

            return file_put_contents($path . "/{$className}.php", $resData);
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage() . $th->getLine());
        }
    }
}
