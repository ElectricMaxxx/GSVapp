<?php


namespace Event\Form\Filter;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * InputFilter definition for the user form for validation issues.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class Donation implements InputFilterAwareInterface
{
    /**
     * @var InputFilter
     */
    private $inputFilter;

    /**
     * {@inheritDoc}
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    /**
     * Retrieve input filter
     *
     * @return InputFilterInterface
     */
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}
