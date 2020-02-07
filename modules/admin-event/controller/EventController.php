<?php
/**
 * EventController
 * @package admin-event
 * @version 0.0.1
 */

namespace AdminEvent\Controller;

use LibFormatter\Library\Formatter;
use LibForm\Library\Form;
use LibPagination\Library\Paginator;
use Event\Model\Event;
use AdminSiteMeta\Library\Meta;
use LibUpload\Library\Form as UForm;

class EventController extends \Admin\Controller
{
    private function getParams(string $title): array{
        return [
            '_meta' => [
                'title' => $title,
                'menus' => ['event']
            ],
            'subtitle' => $title,
            'pages' => null
        ];
    }

    public function editAction(){
        if(!$this->user->isLogin())
            return $this->loginFirst(1);
        if(!$this->can_i->manage_event)
            return $this->show404();

        $event = (object)[];

        $id = $this->req->param->id;
        if($id){
            $event = Event::getOne(['id'=>$id]);
            if(!$event)
                return $this->show404();
            Meta::parse($event, 'meta');
            UForm::parse($event, 'cover');
            $params = $this->getParams('Edit Event');
        }else{
            $params = $this->getParams('Create New Event');
        }

        $form              = new Form('admin.event.edit');
        $params['form']    = $form;
        
        if(!($valid = $form->validate($event)) || !$form->csrfTest('noob'))
            return $this->resp('event/edit', $params);


        Meta::combine($valid, 'meta');
        UForm::combine($valid, 'cover');
        
        if($id){
            if(!Event::set((array)$valid, ['id'=>$id]))
                deb(Event::lastError());
        }else{
            $valid->user = $this->user->id;
            if(!Event::create((array)$valid))
                deb(Event::lastError());
        }

        // add the log
        $this->addLog([
            'user'   => $this->user->id,
            'object' => $id,
            'parent' => 0,
            'method' => $id ? 2 : 1,
            'type'   => 'event',
            'original' => $event,
            'changes'  => $valid
        ]);

        $next = $this->router->to('adminEvent');
        $this->res->redirect($next);
    }

    public function indexAction(){
        if(!$this->user->isLogin())
            return $this->loginFirst(1);
        if(!$this->can_i->manage_event)
            return $this->show404();

        $cond = $pcond = [];
        if($q = $this->req->getQuery('q'))
            $pcond['q'] = $cond['q'] = $q;

        list($page, $rpp) = $this->req->getPager(25, 50);

        $events = Event::get($cond, $rpp, $page, ['created'=>false]) ?? [];
        if($events)
            $events = Formatter::formatMany('event', $events, ['user']);

        $params             = $this->getParams('Events');
        $params['events']   = $events;
        $params['form']     = new Form('admin.event.index');

        $params['form']->validate( (object)$this->req->get() );

        // pagination
        $params['total'] = $total = Event::count($cond);
        if($total > $rpp){
            $params['pages'] = new Paginator(
                $this->router->to('adminEvent'),
                $total,
                $page,
                $rpp,
                10,
                $pcond
            );
        }

        $this->resp('event/index', $params);
    }

    public function removeAction(){
        if(!$this->user->isLogin())
            return $this->loginFirst(1);
        if(!$this->can_i->manage_event)
            return $this->show404();

        $id     = $this->req->param->id;
        $event  = Event::getOne(['id'=>$id]);
        $next   = $this->router->to('adminEvent');
        $form   = new Form('admin.event.index');

        if(!$form->csrfTest('noob'))
            return $this->res->redirect($next);

        // add the log
        $this->addLog([
            'user'   => $this->user->id,
            'object' => $id,
            'parent' => 0,
            'method' => 3,
            'type'   => 'event',
            'original' => $event,
            'changes'  => null
        ]);

        Event::remove(['id'=>$id]);

        $this->res->redirect($next);
    }
}