<?php


namespace App\Http\Handlers;


class BaseHandler
{
    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @param $errors
     */
    public function setErrors($errors): void
    {
        $this->errors = is_array($errors) ? $errors : (array)$errors;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
