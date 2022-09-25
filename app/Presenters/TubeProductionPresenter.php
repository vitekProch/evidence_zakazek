<?php

namespace App\Presenters;

use Nette\Application\UI\Form;
use Nette;
class TubeProductionPresenter extends BasePresenter
{

    public function renderProduction(int $page = 1)
    {
        $paginator = new Nette\Utils\Paginator;
        $this->template->maxPage = $paginator->getPageCount();
        $this->template->page = $paginator->getPage();
        $productionCount = $this->tubeProductionModel->getCountAllProduction();
        $paginator = new Nette\Utils\Paginator;
        $paginator->setItemCount($productionCount); // celkový počet položek, je-li znám
        $paginator->setItemsPerPage(10); // počet položek na stránce
        $paginator->setPage($page); // číslo aktuální stránky

        $tube_production = $this->tubeProductionModel->getTubeProduction($paginator->getLength(), $paginator->getOffset());
        $this->template->tube_production = $tube_production;
        $this->template->paginator = $paginator;

    }
    public function renderEdit(int $id): void
    {
        $tube_production = $this->tubeProductionModel->getTubeProduction(1,0);
        $material_id = $this->tubeProductionModel->getOrderById()->get($id);
        $employee_name = $this->employeeModel->getEmployeeName($material_id->employee_id);
        $employee_id = $material_id->toArray()["employee_id"];

        $this->template->employee_id = $employee_id;
        $this->template->tube_production = $tube_production;
        $this->template->material_id = $material_id;
        $this->template->employee_name = $employee_name;
    }
    public function actionEdit(int $id): void
    {
        $order_value = $this->tubeProductionModel->getOrderById()->get($id);
        if (!$order_value) {
            $this->error('Uživatel nebyl nalezen');
        }
        if ($this->user->id == $order_value->toArray()["employee_id"]) {
            $this['editForm']->setDefaults($order_value->toArray());
            }
        else{
            $this->flashMessage("Nemáte oprávění upravit tuto zakázku","error");
        }
    }
    public function handleDelete(int $id)
    {
        $this->tubeProductionModel->deleteRecord($id);
        $this->flashMessage('Zakázka byla odstraněna.', 'success');
        $this->redirect('TubeProduction:production');
    }

    protected function createComponentEditForm(): Form
    {
        $form = new Form;
        $form->addText('material_id', 'číslo zakázky')
            ->addRule($form::LENGTH, 'Číslo zakázky může být krátné minimálně 6 číslic',[6,7])
            ->addRule($form::NUMERIC, 'Číslo zakázky se musí skládat pouze z číslic')
            ->setRequired('Vyplňte prosím %label');
        $form->addHidden('id');
        $diameter = $this->tubeDiameterModel->getDiameters();
        $form->addSelect('diameter_id', 'Průměr: ', $diameter);

        $form->addText('made_quantity', 'počet kusů')
            ->addRule($form::NUMERIC, 'Počet kusů se musí skládat pouze z číslic')
            ->addRule($form::MAX_LENGTH, 'Počet kusů může mít maximálně %d znaků', 4)
            ->setRequired('Vyplňte prosím %label');

        $shifts = [
            '1' => 'Ranní',
            '2' => 'Odpolední',
            '3' => 'Noční',
        ];

        $form->addSelect('shift_id', 'Směna: ', $shifts);
        $form->setDefaults(["shift" => 3, "diameter" => 2]);
        $form->addSubmit('save', 'Uložit');

        $form->onSuccess[] = [$this, 'editFormSucceeded'];
        return $form;
    }
    public function editFormSucceeded(Form $form, array $values): void
    {
       $this->tubeProductionModel->updateNewData(
           $values['id'],
           $values['material_id'],
           $values['diameter_id'],
           $values['made_quantity'],
           $values['shift_id']);

        $this->flashMessage('Zakázka byla upravena.', 'success');
        $this->redirect('TubeExcess:tubeExcess');
    }
    public function handleShow(int $id, array $pproduct)
    {

        $this->template->id = $id;
        $this->template->productt = $pproduct;
        if ($this->isAjax()) {
            $this->payload->isModal = TRUE;
            $this->redrawControl("modal");

        }
    }
    public function handleLoadProducts($page){
        if($this->isAjax()){
            $this->redrawControl('pagination');
            $this->redrawControl('filterListing');
        }
    }
}
