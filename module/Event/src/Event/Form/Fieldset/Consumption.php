<?php


namespace Event\Form\Fieldset;


use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

/**
 * FieldSet definition for the Consumption, to map the form as a collection to the events.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class Consumption extends Fieldset implements InputFilterProviderInterface
{
    public function __construct($manager)
    {
        parent::__construct('consumption');

        $this->setHydrator(new DoctrineHydrator($manager, 'Event\Doctrine\Orm\Consumption'))->setObject(new \Event\Doctrine\Orm\Consumption());

        ;

        $this->add(array(
            'name' => 'meal',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Speisen',
                'object_manager' => $manager,
                'target_class' => 'Event\Doctrine\Orm\Meal',
                'property' => 'name',
            ),
            'attributes' => array(
                'required' => 'required',
            ),
        ));
        $this->add(array(
            'name' => 'amountOf',
            'type' => 'Number',
            'options' => array(
                'label' => 'Anzahl',
            ),
            'attributes' => array(
                'required' => 'required',
            ),
        ));
        $this->add(array(
            'name' => 'user',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => 'Mitglied',
                'object_manager' => $manager,
                'target_class' => 'Event\Doctrine\Orm\User',
                'property' => 'username',
            ),
            'attributes' => array(
                'required' => 'required',
            ),
        ));


        $this->setAttribute('class', 'fieldset-collection consumption');
    }

    /**
     * {@inheritDoc}
     */
    public function getInputFilterSpecification()
    {
        return array();
    }
}
