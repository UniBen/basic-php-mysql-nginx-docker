<?php

namespace Framework;

use Framework\Util\Arr;
use Framework\Traits\LogsToConsole;

/**
 * Class Command
 * Usage:
 * 1. Create a new class in app/commands and extend this class.
 * 2. Implement the abstract handle method.
 * 3. Call the class using command php command <ClassName>
 *   * To pass arguments to the command just add them after <ClassName>
 *     E.g: php command <ClassName> <Arg1> <Arg2>
 * @package Framework
 */
abstract class Command
{
    use LogsToConsole;

    /**
     * @var array
     */
    protected $args;

    /**
     * @var array
     */
    protected $modifiers = [];

    /**
     * Command constructor.
     *
     * @param array $args
     */
    public function __construct(array $args = []) {
        $modifiers = array_merge($this->modifiers, ['--help']);

        foreach ($args as $key => $arg) {
            if (in_array($arg, $modifiers)) {
                $this->modifiers[] = $arg;
                unset($args[$key]);
            } else if (preg_match('/-{1,2}.*/', $arg)) {
                unset($args[$key]);
            }
        }

        $this->args = $args;
    }

    /**
     * @return mixed
     */
    public abstract function handle();

    /**
     * @param $args $args
     *
     * @return void
     */
    public function setArgs(array $args) {
        $this->args  = $args;
    }

    /**
     * @param string|int $key
     *
     * @return mixed
     */
    public function argument($key = null) {
        if (!is_null($key)) {
            return Arr::get($this->args, $key);
        }

        return $this->args;
    }
}