<?php


namespace Event\Controller;

use Doctrine\ORM\EntityManager;
use Event\Model\ExchangeArrayInterface;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Navigation\Navigation;
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

    public function listAction()
    {
        return $this->renderView(
            'list',
            array(
                'list' => $this->getRepositoryForCurrentClass()->findAll()
            ),
            true
        );
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute($this->baseRoutePattern, array(
                'action' => 'add'
            ));
        }

        $subject = $this->getRepositoryForCurrentClass()->find($id);
        if (!$subject) {
            $this->notFoundAction();
        }
        $this->postLoad($subject);

        $this->form->bind($subject);
        $this->form->get('submit')->setAttribute('value', 'Speichern');

        $request = $this->getRequest();
        if ($request->isPost()) {
            if (null !== $this->inputFilter) {
                $this->form->setInputFilter($this->inputFilter->getInputFilter());
            }

            $this->form->setData($request->getPost());
            if ($this->form->isValid()) {
                $this->preUpdate($subject);
                $this->manager->persist($subject);
                $this->manager->flush();
                $this->postUpdate($subject);
                return $this->redirect()->toRoute($this->baseRoutePattern);
            }
        }

        return $this->renderView('update', array(
            'actionPath' => $this->url()->fromRoute($this->baseRoutePattern, array('action' => 'edit', 'id' => $id)),
            'id'         => $id,
            'form'       => $this->form,
            'title'      => 'Bearbeiten von: '.$subject,
        ));
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute($this->baseRoutePattern);
        }

        $subject = $this->getRepositoryForCurrentClass()->find($id);
        if (!$subject) {
            $this->notFoundAction();
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $deleteButtonValue = $request->getPost('delete', 'Nein');

            if ($deleteButtonValue == 'Ja') {
                $this->preRemove($subject);
                $this->manager->remove($subject);
                $this->manager->flush();
                $this->postRemove();
            }

            return $this->redirect()->toRoute($this->baseRoutePattern);
        }

        return $this->renderView('delete', array(
            'actionPath' => $this->url()->fromRoute($this->baseRoutePattern, array('action' => 'delete', 'id' => $id)),
            'title'      => 'Löschen von '.$subject,
            'message'    => 'Wollen Sie '.$subject.' wirklich löschen?',
            'id'         => $id,
            'item'       => $subject
        ));
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
                $this->prePersist($subject);
                $this->manager->persist($subject);
                $this->manager->flush();
                $this->postPersist($subject);

                $this->redirect()->toRoute($this->baseRoutePattern);
            }
        }

        return $this->renderView('update', array(
            'actionPath' => $this->url()->fromRoute($this->baseRoutePattern, array('action' => 'add')),
            'form'       => $this->form,
            'title'      => 'Hinzufügen',
        ));
    }

    protected function renderView($template, $data)
    {
        $ownTemplate = false;

        $baseView = new ViewModel(array('title' => 'my Title'));
        $baseView->setTemplate('event/base-view');

        $contentView = new ViewModel($data);
        if (file_exists(__DIR__.'/../../../view/event/'.$this->baseRoutePattern.'/'.$template.'.phtml')) {
            $ownTemplate = true;
        }

        $template = $ownTemplate
            ? 'event/'.$this->baseRoutePattern.'/'.$template
            :'event/default/'.$template;
        $contentView->setTemplate($template);

        $navigationView = new ViewModel();
        $navigationView->setTemplate('event/navigation');

        $baseView
            ->addChild($contentView, 'content')
            ->addChild($navigationView, 'navigation')
        ;

        return $baseView;
    }

    protected function preUpdate($object)
    {

    }

    protected function postUpdate($object)
    {

    }

    protected function prePersist($object)
    {

    }

    protected function postPersist($object)
    {

    }

    protected function postLoad($object)
    {

    }

    protected function preRemove($object)
    {

    }

    protected function postRemove()
    {

    }
}
