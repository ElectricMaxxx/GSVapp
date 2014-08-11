<?php


namespace Event\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Event\Model\ExchangeArrayInterface;
use Zend\Form\Form;
use Zend\Http\Response;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * This controller holds all common base crud operations.
 *
 * Every CRUD controller needs a form class, an input filter
 * a baseRoutePattern, the className which it is responsible for
 * and the doctrine entity manager to handle the persistence.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
class BaseController extends AbstractActionController
{
    /**
     * The form class to display, edit and create the
     * properties of the current class.
     *
     * @var Form
     */
    protected $form;

    /**
     * The FQCN of the current class.
     * This one is needed to get the repository, which
     * handles the class's persistence and to
     * instantiate a new entity.
     *
     * @var string
     */
    protected $className;

    /**
     * InputFilters are uses by the Form to handle the validation.
     *
     * @var InputFilterAwareInterface
     */
    protected $inputFilter;

    /**
     * To persist the entities the doctrine entity manager
     * is used by this controller.
     *
     * @var EntityManager
     */
    protected $manager;

    /**
     * The base Route name, means {name}/{action}/[:id]
     *
     * It is used for detecting the responsible view templates, too.
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

    /**
     * As the repository is used and asked for on several places
     * its creation is encapsulated in one class.
     *
     * @return EntityRepository
     */
    protected function getRepositoryForCurrentClass()
    {
        return $this->manager->getRepository($this->className);
    }

    /**
     * Action to display a list of entities.
     *
     * Create your own template in an folder structure like
     * ...views/event/{entity_name}/list.phhtml
     *
     * @return ViewModel
     */
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

    /**
     * Action to edit an entity with the given Form class, validated by
     * the inputFilter and persisted with the entity manager.
     *
     * Either create your own template in
     * "views/event/{baseRoutePattern}/update.phhtml"
     * or the default one "views/event/default/update.phtml"
     * will be used.
     *
     * Not found entities will be redirected to the create path.
     *
     * @return Response|ViewModel
     */
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

    /**
     * Entities referenced by its ID will be removed by this method.
     *
     * Not found ones will be redirected to list view.
     * The entities won't be deleted directly, a page to confirm
     * will be displayed.
     *
     * @return Response|ViewModel
     */
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

    /**
     * Action to create an entity with the given Form class, validated by
     * the inputFilter and persisted with the entity manager.
     *
     * @return ViewModel
     */
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

    /**
     * Nested views are used to display the same base view for all
     * create, edit, delete and list pages.
     *
     * All of them got a content view to display the forms and list.
     *
     * @return ViewModel
     */
    protected function createBaseView()
    {
        $baseView = new ViewModel(array('title' => 'my Title'));
        $baseView->setTemplate('event/base-view');

        return $baseView;
    }

    /**
     * Calls createBaseView to get the base view to render the current content
     * view into it.
     *
     * Either a custom template in the common path "views/event/{baseRoutePattern}/{action}.phtml"
     * or an default one "views/event/default/{action}.phtml" will be used to render the
     * data into the content view.
     *
     * @param $template
     * @param $data
     * @return ViewModel
     */
    protected function renderView($template, $data)
    {
        $ownTemplate = false;

        $baseView = $this->createBaseView();

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

    /**
     * @todo[max] Finish this method do render Blocks in a tree alike structure by nesting views.
     *
     * @param $blocksData
     * @return ViewModel
     */
    protected function renderBlocks($blocksData)
    {
        $baseView = $this->createBaseView();

        foreach ($blocksData as $block) {
            $view = new ViewModel($block);
            $view->setTemplate($block['template']);

            $baseView->addChild($block, isset($block['name']) ? $block['name'] : null);
        }

        return $baseView;
    }

    /**
     * Hook into the entity lifecycle before updating it.
     *
     * @param $object
     */
    protected function preUpdate($object)
    {

    }

    /**
     * Hook into the entity lifecycle after updating it.
     *
     * @param $object
     */
    protected function postUpdate($object)
    {

    }

    /**
     * Hook into the entity lifecycle before persisting it the first time.
     *
     * @param $object
     */
    protected function prePersist($object)
    {

    }

    /**
     * Hook into the entity lifecycle after persisting it the first time.
     *
     * @param $object
     */
    protected function postPersist($object)
    {

    }

    /**
     * Hook into the entity lifecycle loading an entity in edit/delete view.
     *
     * @param $object
     */
    protected function postLoad($object)
    {

    }

    /**
     * Hook into the entity lifecycle before removing it.
     *
     * @param $object
     */
    protected function preRemove($object)
    {

    }

    /**
     * Do some clearing action after removing something with this
     * CRUD controller.
     */
    protected function postRemove()
    {

    }
}
