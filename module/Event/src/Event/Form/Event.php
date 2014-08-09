<?php


namespace Event\Form;


use Zend\Form\Form;

/**
 * Form creation class for the Meal model.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class Event extends Form
{
    public function __construct($name = null)
    {
        parent::__construct($name);

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name'       => 'name',
            'type'       => 'Text',
            'options'   => array(
                'label' => 'Name des Events (default: Grillen am ...)',
            ),
        ));
        $this->add(array(
            'name' => 'date',
            'type' => 'Text',
            'attributes' => array(
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Datum (TT.MM.JJJJ',
            ),

        ));
        $this->add(array(
            'type' => 'Csrf',
            'name' => 'security',
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
