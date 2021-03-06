<?php
namespace Sandbox\Controller;

use Cake\ORM\TableRegistry;

/**
 * @property \Sandbox\Model\Table\SandboxCategoriesTable $SandboxCategories
 */
class ToolsExamplesController extends SandboxAppController {

	/**
	 * @return void
	 */
	public function index() {
		$actions = $this->_getActions($this);

		$this->set(compact('actions'));
	}

	/**
	 * @deprecated Standalone now as https://github.com/ADmad/CakePHP-tree
	 * @return void
	 */
	public function tree() {
		$this->helpers[] = 'Tools.Tree';

		$this->loadModel('Sandbox.SandboxCategories');

		// Example data added:
		/*
		$data = [
			[
				'name' => 'Alpha',
				'description' => 'First One'
			],
			[
				'name' => 'Beta',
				'description' => 'Second One'
			],
			[
				'name' => 'Gamma',
				'description' => 'Third One'
			],
			[
				'name' => 'Delta',
				'description' => 'Forth One'
			],
			[
				'name' => 'Child of 2nd one',
				'parent_id' => 2,
				'description' => 'Fifth One'
			],
			[
				'name' => 'Child of child',
				'parent_id' => 5,
				'description' => 'Sixth One'
			],
			[
				'name' => 'Child of 4th one',
				'parent_id' => 4,
				'description' => 'Seventh One'
			],
		];

		$entities = $this->SandboxCategories->newEntities($data);
		foreach ($entities as $entity) {
			// Save entity
			$this->SandboxCategories->save($entity);
		}
		*/

		$options = [];
		$tree = $this->SandboxCategories->find('threaded', $options);

		$this->set(compact('tree'));
	}

	/**
	 * //TODO
	 *
	 * @return void
	 */
	public function _bitmasks() {
		$flags = [
			'1' => 'Apple',
			'2' => 'Peach',
			'4' => 'Banana',
			'8' => 'Lemon',
			'16' => 'Coconut',
		];
		$this->Model = TableRegistry::get('Sandbox.BitmaskRecords');
		$this->Model->Behaviors->load('Tools.Bitmasked', ['field' => 'flag', 'bits' => $flags]);

		$records = $this->Model->find('all');

		if ($this->request->is('post')) {
		}

		$this->set(compact('records', 'flags'));
	}

	/**
	 * Slugged behavior and ascii unique URL slugs
	 *
	 * @return void
	 */
	public function slug() {
		$this->Users = TableRegistry::get('Sandbox.SandboxUsers');
		$this->Users->addBehavior('Tools.Slugged', ['mode' => 'ascii', 'unique' => true]);

		$user = $this->Users->newEntity();

		if ($this->request->is(['post', 'put'])) {
			$this->Users->patchEntity($user, $this->request->data);
			if ($this->Users->save($user)) {
				$this->Flash->success('Yeah!');
			} else {
				$this->Flash->error('Please correct your form.');
			}
		}

		$this->set(compact('user'));
	}

	/**
	 * @return void
	 */
	public function password() {
		$this->Users = TableRegistry::get('Users');
		$this->Users->addBehavior('Tools.Passwordable');

		$user = $this->Users->newEntity();

		if ($this->request->is('post')) {
			$this->Users->patchEntity($user, $this->request->data);
			if ($this->Users->save($user)) {
				$this->Flash->success('Yeah!');
			} else {
				$this->Flash->error('Please correct your form.');
			}
		}

		$this->set(compact('user'));
	}

	/**
	 * @return void
	 */
	public function passwordEdit() {
		$this->Users = TableRegistry::get('Users');
		$this->Users->addBehavior('Tools.Passwordable', ['require' => false]);

		$user = $this->Users->newEntity();

		if ($this->request->is('post')) {
			$this->Users->patchEntity($user, $this->request->data);
			if ($this->Users->save($user)) {
				$this->Flash->success('Yeah!');
			} else {
				$this->Flash->error('Please correct your form.');
			}
		}

		$this->set(compact('user'));
		$this->render('password');
	}

	/**
	 * @return void
	 */
	public function passwordEditCurrent() {
		$this->Users = TableRegistry::get('Users');
		$this->Users->addBehavior('Tools.Passwordable', ['require' => false, 'current' => true]);

		$user = $this->Users->newEntity();

		if ($this->request->is('post')) {
			$this->Users->patchEntity($user, $this->request->data);
			if ($this->Users->save($user)) {
				$this->Flash->success('Yeah!');
			} else {
				$this->Flash->error('Please correct your form.');
			}
		}

		$this->set(compact('user'));
	}

	/**
	 * Test validation on marshal and rules on save.
	 *
	 * @return void
	 */
	public function confirmable() {
		$Animals = TableRegistry::get('Sandbox.Animals');
		$Animals->validator()->remove('confirm');
		$Animals->addBehavior('Tools.Confirmable');
		// Bug in CakePHP: You need to manually trigger build on the behavior and pass the validator!
		$Animals->behaviors()->Confirmable->build($Animals->validator());

		$animal = $Animals->newEntity();

		if ($this->request->is('post')) {
			$animal = $Animals->patchEntity($animal, $this->request->data);

			// Simulate $Animals->save($animal) call as we dont't want to really save here
			if (!$animal->errors()) {
				$this->Flash->success('Yeah, you are allowed to continue!');
			} else {
				$this->Flash->error('Please correct your form content!');
			}
		}

		$this->set(compact('animal'));
	}

	/**
	 * @return void
	 */
	public function qr() {
		$types = [
			'text' => 'Text',
			'url' => 'Url',
			'tel' => 'Phone Number',
			'sms' => 'Text message',
			'email' => 'E-Mail',
			'geo' => 'Geo',
			'market' => 'Market',
			'card' => 'Vcard'
		];

		if ($this->request->is('post')) {
			switch ($this->request->data['type']) {
				case 'url':
				case 'tel':
				case 'email':
				case 'geo':
				case 'market':
					$result = str_replace([PHP_EOL, "\n"], ' ', $this->request->data['content']);
					break;
				case 'card':
					$result = $this->request->data['Card'];
					$result['birthday'] = $result['birthday']['year'] . '-' . $result['birthday']['month'] . '-' . $result['birthday']['day'];

					break;
				case 'sms':
					$result = [$this->request->data['Sms']['number'], $this->request->data['Sms']['content']];
					break;
				case 'text':
					$result = $this->request->data['content'];
					break;
				default:
					$result = null;
			}
			$this->set(compact('result'));
		}

		$this->set(compact('types'));
		$this->helpers[] = 'Tools.QrCode';
	}

	/**
	 * @return void
	 */
	public function formatHelper() {
	}

	/**
	 * Display a dynamic timeline.
	 *
	 * @return void
	 */
	public function timeline() {
	}

	/**
	 * Display a gravatar image.
	 *
	 * @return void
	 */
	public function gravatar() {
		$this->helpers[] = 'Tools.Gravatar';
	}

	/**
	 * //TODO
	 *
	 * @return void
	 */
	public function _diff() {
	}

	/**
	 * @return void
	 */
	public function _typography() {
		$this->Common->loadHelper(['Tools.Typography']);
	}

}
