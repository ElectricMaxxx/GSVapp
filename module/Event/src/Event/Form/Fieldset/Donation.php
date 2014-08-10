<?php


namespace Event\Form\Fieldset;

use Event\Doctrine\Orm\Donation as DonationEntity;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

/**
 * Field set class for generating child forms.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class Donation extends Fieldset implements InputFilterProviderInterface
{
    public function __construct($manager)
    {
        parent::__construct('consumption');

        $this->setHydrator(new DoctrineHydrator($manager, 'Event\Doctrine\Orm\Consumption'))
            ->setObject(new DonationEntity());

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
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

    }

    /**
     * {@inheritDoc}
     */
    public function getInputFilterSpecification()
    {
        return array();
    }
}