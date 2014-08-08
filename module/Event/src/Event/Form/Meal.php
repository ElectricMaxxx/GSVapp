<?php


namespace Event\Form;


use Zend\Form\Form;

/**
 * Form creation class for the Meal model.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class Meal extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('meal');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'name',
            'type' => 'Text',
            'options' => array(
                'label' => 'Name',
            ),
        ));
        $this->add(array(
            'name' => 'price',
            'type' => 'Number',
            'options' => array(
                'label' => 'Preis',
            ),
        ));
        $this->add(array(
            'name' => 'description',
            'type' => 'Text',
            'options' => array(
                'label' => 'Beschreibung',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
    }
}
