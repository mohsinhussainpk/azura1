<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class StreamerPassword extends Constraint
{
    public $message;

    public function __construct($options = null)
    {
        $this->message = __('Password cannot contain the following characters: %s', '{{ chars }}');

        parent::__construct($options);
    }
}
