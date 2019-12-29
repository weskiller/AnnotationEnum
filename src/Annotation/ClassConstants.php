<?php

namespace Weskiller\Enum\Annotation;

use Weskiller\Enum\Exception\BadEnumAnnotationsException;

class ClassConstants
{
    /**
     * @var \ReflectionClassConstant
     */
    protected \ReflectionClassConstant $reflectionClassConstant;

    /**
     * @var array
     */
    protected array $annotations = [];

    /**
     * @var bool
     */
    protected bool $deal = false;

    public function __construct($class,$constantName)
    {
        $this->reflectionClassConstant = new \ReflectionClassConstant($class,$constantName);
    }

    private function getAnnotations()
    {
        if(!$this->deal) {
            $this->annotations = $this->analyseAnnotation();
            $this->deal = true;
        }
        return $this->annotations;
    }

    private function analyseAnnotation()
    {
        $annotations = [];
        if(preg_match_all('/@([a-zA-Z_]+)[\s]+(.+)[\s]*\n/i',$this->reflectionClassConstant->getDocComment(),$matches)) {
            $count = count($matches[1]);
            for($i = 0 ;$i < $count ;$i++) {
                $var = $matches[1][$i];
                $val = trim($matches[2][$i]);

                //string
                if(($leftBorder = $val[0]) === ($rightBorder = $val[strlen($val) - 1]) and ($leftBorder === "'" or $leftBorder === '"')) {
                    $annotations[$var] = trim($val,"'|\"");
                }
                //array
                else if($leftBorder == '[' and $rightBorder == ']') {
                    $annotations[$var] = $this->eval($val,false);
                }
                //expression
                else if($leftBorder == '(' and $rightBorder == ')')
                {
                    $annotations[$var] = $this->eval($val,true);
                }
                //json
                else if($leftBorder == '{' and $rightBorder == '}' and $array = @json_decode($val,true)) {
                    $annotations[$var] = $array;
                }
                //numeric
                else if(is_numeric($val)) {
                    $annotations[$var] = strval($val);
                }
                //not expect
                else {
                    throw new BadEnumAnnotationsException(sprintf('bad enum annotation %s(%s)',static::class,$var));
                }
            }
        }
        return $annotations;
    }

    private function eval($value,bool $isClosure = false)
    {
        $reflectionClassConstant = $this->reflectionClassConstant;

        $callback = function () use($value,$reflectionClassConstant) {
            try {
                @eval('$t=' . $value . ';');
                return $t ?? null;
            } catch (\Throwable $exception) {
                throw new BadEnumAnnotationsException(
                    sprintf(
                        'bad enum annotation %s::%s "%s", throw message(%s)',
                        $reflectionClassConstant->class,
                        $reflectionClassConstant->name,
                        $value,
                        $exception->getMessage()
                    )
                );
            }
        };
        if($isClosure) {
            return $callback;
        }
        return call_user_func($callback);
    }

    public function get($name)
    {
        $value = $this->getAnnotations()[$name] ?? null;
        if($value instanceof \Closure) {
            $void = new Class {};
            return $value->call($void);
        }
        return $value;
    }
}