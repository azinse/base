<?php
namespace Base\MainBundle\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates values are greater than or equal to the previous (>=).
 *
* @author Frédéric Richaudeau
*/
class GreaterThanOrEqualValidator extends ConstraintValidator
{
   /**
     * @inheritDoc
     */
    public function validate($value,  Constraint $constraint)
    {
        //value : valeur du champ en cours de test
        // debut : date de comparaison
//var_dump($value);
//var_dump( $this->context->getRoot()->get($constraint->debut)->getData());
//var_dump($this);
//exit;
        
        //if ($value < $constraint->debut) {
        if ($value != null && $value < $this->context->getRoot()->get($constraint->debut)->getData()) {       
            $this->context->addViolation($constraint->message, array(
                '{{ value }}' => $value,
                '{{ debut }}' => $constraint->debut,
            ));
            return false;
        }
    return true;
    }
}
