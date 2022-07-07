<?php
declare(strict_types=1);

use Nette\Forms\Form;

$form = new Form;
$form->addText('name', 'Jméno:')
    ->setRequired();

$form->addPassword('password', 'Heslo:')
    ->setRequired();

$form->addSubmit('send', 'Registrovat');


if ($form->isSuccess()) {
    echo 'Formulář byl správně vyplněn a odeslán';
    $data = $form->getValues();
    var_dump($data);
    // $data->name obsahuje jméno
    // $data->password obsahuje heslo

    // zpracujeme data a přesměrujeme na jinou stránku
}
$form->render();