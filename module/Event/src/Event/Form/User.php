<?php


namespace Event\Form;


use Doctrine\Common\Persistence\ObjectManager;
use Event\Form\Fieldset\Donation;
use Zend\Form\Form;

/**
 * Form creation class for the Meal model.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class User extends Form
{
    public function __construct($name = null, ObjectManager $manager)
    {
        parent::__construct($name);


        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

        $this->add(array(
            'name'       => 'username',
            'type'       => 'Text',
            'attributes' => array(
                'required' => 'required',
            ),
            'options'   => array(
                'label' => 'Username',
            ),
        ));
        $this->add(array(
            'name' => 'email',
            'type' => 'Email',
            'attributes' => array(
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Email',
            ),
        ));
        $this->add(array(
            'name' => 'donations',
            'type' => 'Zend\Form\Element\Collection',
            'options' => array(
                'label' => 'Gutschriften',
                'should_create_template' => true,
                'template_placeholder' => '__placeholder__',
                'allow_add' => true,
                'target_element' => new Donation($manager),
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
