<?php


namespace Event\Controller;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Event\Model\ExchangeArrayInterface;
use SebastianBergmann\Exporter\Exception;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Custom controller for this module to get rid of some code duplications.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class BaseController extends AbstractActionController
{
    /**
     * The current form.
     *
     * @var Form
     */
    protected $form;

    /**
     * The current class name.
     *
     * @var string
     */
    protected $className;

    /**
     * @var InputFilterAwareInterface
     */
    protected $inputFilter;

    /**
     * @var EntityManager
     */
    protected $manager;

    /**
     * The base Route name, means {name}/{action}/[:id]
     *
     * @var string
     */
    protected $baseRoutePattern;

    /**
     * @param EntityManager $manager
     */
    public function setManager($manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param InputFilterAwareInterface $inputFilter
     */
    public function setInputFilter(InputFilterAwareInterface $inputFilter)
    {
        $this->inputFilter = $inputFilter;
    }

    /**
     * @param Form $form
     */
    public function setForm($form)
    {
        $this->form = $form;
    }

    /**
     * @param string $className
     */
    public function setClassName($className)
    {
        $this->className = $className;
    }

    /**
     * @param string $baseRoutePattern
     */
    public function setBaseRoutePattern($baseRoutePattern)
    {
        $this->baseRoutePattern = $baseRoutePattern;
    }

    protected function getRepositoryForCurrentClass()
    {
        return $this->manager->getRepository($this->className);
    }

    public function indexAction()
    {
        return new ViewModel(array('list' => $this->getRepositoryForCurrentClass()->findAll()), array());
    }

    public function editAction()
    {

    }

    public function deleteAction()
    {

    }

    public function addAction()
    {
        $this->form->get('submit')->setValue('Hinzufügen');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $subject = new $this->className();
            if (null != $this->inputFilter) {
                $this->form->setInputFilter($this->inputFilter->getInputFilter());
            }

            $this->form->setData($request->getPost());

            if ($this->form->isValid() && $subject instanceof ExchangeArrayInterface) {
                $subject->exchangeArray($this->form->getData());
                $this->manager->persist($subject);
                $this->manager->flush();

                $this->redirect()->toRoute($this->baseRoutePattern);
            }
        }

        return array('form' => $this->form);
    }
}
