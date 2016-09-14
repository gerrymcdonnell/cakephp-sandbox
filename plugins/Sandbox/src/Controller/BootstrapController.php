<?php
namespace Sandbox\Controller;

use Cake\Event\Event;

class BootstrapController extends SandboxAppController {

	/**
	 * @var string
	 */
	public $modelClass = 'Sandbox.Animals';

	/**
	 * @var array
	 */
	public $components = ['Search.Prg'];

	/**
	 * @var array
	 */
	public $helpers = [
		'Form' => ['className' => 'BootstrapUI.Form'],
		'Flash' => ['className' => 'BootstrapUI.Flash']
	];

	/**
	 * @param \Cake\Event\Event $event
	 * @return void
	 */
	public function beforeFilter(Event $event) {
		$this->components()->unload('Flash');
		$this->loadComponent('Flash');

		unset($this->helpers['Tools.Form']);
		unset($this->helpers['Tools.Flash']);

		parent::beforeFilter($event);

	}

	/**
	 * @return void
	 */
	public function index() {
		$actions = $this->_getActions($this);

		$this->set(compact('actions'));
	}

	/**
	 * @return void
	 */
	public function form() {
		$animal = $this->Animals->newEntity();

		$this->set(compact('animal'));
	}

	/**
	 * @return void
	 */
	public function localized() {
		$animal = $this->Animals->newEntity();

		// This hack is needed to prevent the forms from being autofilled with todays date
		$this->request->data['discovered'] = '';
		$this->request->data['published'] = '';
		$this->request->data['time'] = '';

		$this->set(compact('animal'));
	}

	/**
	 * @return void
	 */
	public function time() {
		$animal = $this->Animals->newEntity();

		// This hack is needed to prevent the forms from being autofilled with todays date
		$this->request->data['time'] = '';
		$this->request->data['time_with_seconds'] = '';

		$this->set(compact('animal'));
	}

	/**
	 * Show how posted forms behave and how to set default values.
	 *
	 * @return void|\Cake\Network\Response
	 */
	public function formPost() {
		$animal = $this->Animals->newEntity();

		if ($this->request->is(['post', 'put'])) {
			// Save form data
			if (false) {
				$this->Flash->success('Saved and redirected');
				return $this->redirect(['action' => 'formPost']);
			}
			$this->Flash->error('Not saved - for demo purposes');

		} else {
			// Here we can set the form defaults, including the ones coming from DB

			$this->request->data['multiple_checkboxes'] = [1, 3, 5];
			$this->request->data['multiple_selects'] = [2, 4];
		}

		$this->set(compact('animal'));
	}

	/**
	 * Show flash message output.
	 *
	 * @param string|null $type
	 * @return void|\Cake\Network\Response
	 */
	public function flash($type = null) {
		$types = ['error', 'warning', 'success', 'info'];
		$this->set(compact('types'));

		if (!$type || !in_array($type, $types, true)) {
			$type = 'error';
		}
		$this->Flash->{$type}('I am a message of type ' . $type . '.');

		// This is for CakePHP 3.1
		/*
		$this->Flash->error('I am an <b>error</b>');
		$this->Flash->success('I am an success message');
		$this->Flash->warning('I am a warning');
		$this->Flash->info('I am a info message');
		*/
	}

}
