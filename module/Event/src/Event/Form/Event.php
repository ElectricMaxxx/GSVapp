<?php


namespace Event\Form;


use Doctrine\ORM\EntityManager;
use Event\Form\Fieldset\Consumption;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
/**
 * Form creation class for the Meal model.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class Event extends Form
{
    public function __construct($name = null, EntityManager $manager)
    {
        parent::__construct($name);
        $this
            ->setAttribute('method', 'post')
            ->setHydrator(new ClassMethodsHydrator(false))
            ->setInputFilter(new InputFilter());

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
           'name' => 'consumptions',
            'type' => 'Zend\Form\Element\Collection',
            'options' => array(
                'label' => 'Verzehrbestellungen oder Nachträge',
                'should_create_template' => true,
                'template_placeholder' => '__placeholder__',
                'allow_add' => true,
                'target_element' => new Consumption($manager),
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
