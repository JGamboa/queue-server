<?php

namespace App\Models;

class State
{
    const PENDING = 'pending';
    const PROCESSING = 'processing';
    const SUCCESS = 'success';
    const FAIL = 'fail';
    const RETRY = 'retry';
    const DECLINED = 'declined';

    protected static $variants
        = [
            self::PENDING => 'pending',
            self::FAIL => 'fail',
            self::PROCESSING => 'processing',
            self::SUCCESS => 'success',
            self::RETRY => 'retry',
            self::DECLINED => 'retry',
        ];

    protected static $labels
        = [
            self::PENDING => '<div class="badge badge-light">PENDING</div>',
            self::FAIL => '<div class="badge badge-danger">FAIL</div>',
            self::PROCESSING => '<div class="badge badge-info">PROCESSING</div>',
            self::SUCCESS => '<div class="badge badge-success">SUCCESS</div>',
            self::RETRY => '<div class="badge badge-dark">RETRY</div>',
            self::DECLINED => '<div class="badge badge-warning">DECLINED</div>',
        ];

    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(string $value)
    {
        if (!isset(self::$variants[$value])) {
            throw new \InvalidArgumentException();
        }
        $this->value = $value;
    }

    public function value()
    {
        return $this->value;
    }

    public function name()
    {
        return self::$variants[$this->value];
    }

    public function label()
    {
        return self::$labels[$this->value];
    }
    
    public function __toString()
    {
        return $this->value;
    }
}
