<?php

namespace App\Presenters;
use App\Forms\SignInFormFactory;
use App\Forms\SignUpFormFactory;
use Nette\Application\UI\Form;



class SignPresenter extends BasePresenter
{
    /**
     * @var SignUpFormFactory
     * @inject
     */
    public SignUpFormFactory $signUpFormFactory;

    /**
     * @var SignInFormFactory
     * @inject
     */
    public SignInFormFactory $signInFormFactory;

    protected function afterRender()
    {
        parent::afterRender();
        $this['signInForm']->setValues(array(
            'shift' => $this->signModel->automaticShiftSelect()
        ), true);
    }


    protected function createComponentSignUpForm(): Form
    {
        return $this->signUpFormFactory->create();
    }


    protected function createComponentSignInForm(): Form
    {
        return $this->signInFormFactory->create();
    }

    public function actionOut(): void
    {
        $this->user->logout(true);
        $this->getUser()->logout();
        $this->flashMessage('Odhlášení bylo úspěšné.','success');
        $this->redirect('Sign:in');
    }

}