<?php
namespace Base\MainBundle\Component\Validator\Constraints;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @author Frédéric Richaudeau
*/

class GreaterThanOrEqual extends Constraint
{
    public $message = 'La date butoir doit être supérieure à la date de début';
    public $debut;

        /**
     * {@inheritDoc}
     */
    public function getDefaultOption()
    {
        return 'debut';
    }

    /**
     * {@inheritDoc}
     */
    public function getRequiredOptions()
    {
        return array('debut');
    }
}
