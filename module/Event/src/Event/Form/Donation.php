<?php


namespace Event\Form;


use Doctrine\Common\Persistence\ObjectManager;
use Zend\Form\Form;

/**
 * Form creation class for the Meal model.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class Donation extends Form
{
    public function __construct($name = null, ObjectManager $manager)
    {
        parent::__construct($name);

        $this->add(array(
            'type' => 'Csrf',
            'name' => 'security',
        ));
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
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
        $this->add(array(
            'name'       => 'value',
            'type'       => 'Number',
            'attributes' => array(
                'required' => 'required',
            ),
            'options'   => array(
                'label' => 'Gutschrift in cent',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton',
                'class' => 'button-success pure-button'
            ),
        ));

        $this->setAttribute('class', 'pure-form-stacked');
    }
}
