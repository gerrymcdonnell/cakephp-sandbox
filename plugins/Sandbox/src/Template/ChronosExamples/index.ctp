<?php
/**
 * @var \App\View\AppView $this
 */
use Cake\Chronos\Chronos;
?>

<h2>Chronos (extends DateTime)</h2>

<?php
Chronos::setTestNow($now);

echo $this->Form->create();
?>

<h3>Now</h3>
<?php
printf("Now: %s", Chronos::now());
?>

<br>
Use a different date for "now":
<?php
echo $this->Form->date('now', ['minYear' => date('Y') - 1, 'maxYear' => date('Y') + 1, 'default' => Chronos::now()]);
?>

<h3>Timezone</h3>
<?php echo Chronos::now()->timezoneName; ?>


<h3>Birthday and Age</h3>
1987-03-04: Age <?php echo Chronos::create('1987', '03', '04')->age; ?>

<h3>Check your birthday or age</h3>
<?php

echo $this->Form->date('birthday', ['minYear' => date('Y') - 80]);

if (!empty($this->request->data['birthday'])) {
	$birthdayString = $this->request->data['birthday']['year'] . '-' . $this->request->data['birthday']['month'] . '-' . $this->request->data['birthday']['day'];
	if (strlen($birthdayString) === 10) {
		$birthday = new Chronos($birthdayString);
	} else {
		$birthday = null;
	}

	$isBirthday = false;
	if (isset($birthday)) {
		$isBirthday = $birthday->isBirthday($now);
	}

	echo '<br>';
	echo 'Birthday: ' . $this->Format->yesNo($isBirthday);

	echo '<br>';
	echo 'Age: ' . ($birthday ? $birthday->age : '-');
}

?>

<br><br>

<?php
echo $this->Form->submit();
echo $this->Form->end();
?>

