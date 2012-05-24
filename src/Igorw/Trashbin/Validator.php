<?php

namespace Igorw\Trashbin;

class Validator
{
    public function validate(array $paste)
    {
        $errors = array();

        if ('' === trim($paste['content'])) {
            $errors[] = 'you must enter some content';
        }

        return $errors;
    }
}
