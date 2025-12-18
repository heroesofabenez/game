<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Model\Request;
use HeroesofAbenez\Model\RequestNotFoundException;
use HeroesofAbenez\Model\CannotSeeRequestException;
use HeroesofAbenez\Model\CannotAcceptRequestException;
use HeroesofAbenez\Model\CannotDeclineRequestException;
use HeroesofAbenez\Model\RequestAlreadyHandledException;

/**
 * Presenter Request
 *
 * @author Jakub Konečný
 */
final class RequestPresenter extends BasePresenter
{
    public function __construct(private readonly Request $model)
    {
        parent::__construct();
    }

    public function renderDefault(): void
    {
        $this->template->requests = $this->model->listOfRequests();
    }

    /**
     * @throws \Nette\Application\BadRequestException
     */
    public function renderView(int $id): void
    {
        try {
            $this->template->request = $this->model->show($id);
        } catch (CannotSeeRequestException) {
            $this->flashMessage("errors.request.cannotSee");
            $this->forward("Homepage:");
        } catch (RequestNotFoundException) {
            throw new \Nette\Application\BadRequestException();
        }
    }

    public function actionAccept(int $id): never
    {
        try {
            $this->model->accept($id);
            $this->flashMessage("messages.request.accepted");
            $this->redirect("Homepage:");
        } catch (RequestNotFoundException) {
            $this->forward("notfound");
        } catch (CannotSeeRequestException) {
            $this->flashMessage("errors.request.cannotSee");
            $this->forward("Homepage:");
        } catch (CannotAcceptRequestException) {
            $this->flashMessage("errors.request.cannotAccept");
            $this->forward("Homepage:");
        } catch (RequestAlreadyHandledException) {
            $this->flashMessage("errors.request.handled");
            $this->forward("Homepage:");
        } catch (\Nette\NotImplementedException) {
            $this->flashMessage("errors.request.typeNotImplemented");
            $this->forward("Homepage:");
        }
    }

    public function actionDecline(int $id): never
    {
        try {
            $this->model->decline($id);
            $this->flashMessage("messages.request.declined");
            $this->redirect("Homepage:");
        } catch (RequestNotFoundException) {
            $this->forward("notfound");
        } catch (CannotSeeRequestException) {
            $this->flashMessage("errors.request.cannotSee");
            $this->forward("Homepage:");
        } catch (CannotDeclineRequestException) {
            $this->flashMessage("errors.request.cannotDecline");
            $this->forward("Homepage:");
        } catch (RequestAlreadyHandledException) {
            $this->flashMessage("errors.request.handled");
            $this->forward("Homepage:");
        }
    }
}
