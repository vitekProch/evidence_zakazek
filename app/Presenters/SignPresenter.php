<?php

namespace App\Presenters;
use App\Model\Facades\UserManager;
use Nette\Application\UI\Form;
use App\Exceptions;
class SignPresenter extends BasePresenter
{

    private UserManager $userManager;
    /**
     * @var mixed
     */

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }
    //Registration
    protected function createComponentSignUpForm(): Form
    {
        $form = new Form;
        $form->addText('username', 'Jméno zaměstnance: ')
            ->setRequired('Vyplňte prosím %label');

        $form->addText('employee_id', 'Číslo zaměstnance: ')
            ->addRule($form::NUMERIC, 'Číslo zaměstnance se musí skládat pouze z číslic')
            ->addRule($form::MIN_LENGTH, 'Číslo musí mít alespoň %d znaků', 4)
            ->setRequired('Vyplňte prosím %label');

        $form->addPassword('password', 'Heslo: ');
        $form->addPassword('passwordVerify', 'Heslo pro kontrolu:')
            ->setRequired('Zadejte prosím heslo ještě jednou pro kontrolu')
            ->addRule($form::EQUAL, 'Hesla se neshodují', $form['password'])
            ->setOmitted();

        $form->addSubmit('send', 'Registrovat');
        $form->onSuccess[] = [$this, 'formSucceeded'];
        return $form;

    }
    public function formSucceeded(Form $form, $data): void
    {
        try {
            $this->userManager->add($data->username,$data->password, $data->employee_id);
            $this->flashMessage('Zaměstnanec byl úspěšně registrován.', 'success');
            $this->redirect('Homepage:');
        }
        catch (Exceptions\DuplicateNameException $e){
            $form->addError("Číslo zaměstnance již existuje");
        }
    }

    //Sign In

    protected function createComponentSignInForm(): Form
    {
        $form = new Form;
        $form->addText('username', 'Uživatelské jméno:')
            ->setRequired('Prosím vyplňte své uživatelské jméno.');

        $form->addPassword('password', 'Heslo:')
            ->setRequired('Prosím vyplňte své heslo.');
        $shifts = [
            '1' => 'Ranní',
            '2' => 'Odpolední',
            '3' => 'Noční',
        ];

        $form->addSelect('shift', 'Směna: ', $shifts);

        $form->addSubmit('send', 'Přihlásit');

        $form->onSuccess[] = [$this, 'signInFormSucceeded'];

        return $form;


    }
    public function signInFormSucceeded(Form $form, \stdClass $values): void
    {
        try {
            $this->user->setExpiration('9 hour');
            $this->getUser()->login($values->username, $values->password);
            $this->employeeModel->insertShift($values->shift, $values->username);
            $this->flashMessage('Přihlášení bylo úspěšné.','success');
            $this->redirect('Homepage:');

        } catch (Exceptions\IncorrectNameException $e) {
            $form->addError("Nesprávné uživatelské jméno");
        } catch (Exceptions\IncorrectPassword $e) {
            $form->addError("Nesprávné uživatelské heslo");
        }
    }

    //Sign Out
    public function actionOut(): void
    {
        $this->user->logout(true);
        $this->getUser()->logout();
        $this->flashMessage('Odhlášení bylo úspěšné.','success');
        $this->redirect('Sign:in');
    }
}