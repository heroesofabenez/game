<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use HeroesofAbenez\Forms\CreateCharacterFormFactory;
use Nette\Application\UI\Form;
use Nextras\Orm\Collection\ICollection;

  /**
   * Presenter Character
   * 
   * @author Jakub Konečný
   */
final class CharacterPresenter extends BasePresenter {
  protected \HeroesofAbenez\Model\Profile $model;
  protected \HeroesofAbenez\Model\UserManager $userManager;
  /** @var ICollection|\HeroesofAbenez\Orm\CharacterClass[] */
  protected ICollection $classes;
  /** @var ICollection|\HeroesofAbenez\Orm\CharacterRace[] */
  protected ICollection $races;
  protected CreateCharacterFormFactory $createCharacterFormFactory;
  
  public function __construct(\HeroesofAbenez\Model\Profile $model, \HeroesofAbenez\Model\UserManager $userManager) {
    parent::__construct();
    $this->model = $model;
    $this->userManager = $userManager;
  }

  public function injectCreateCharacterFormFactory(CreateCharacterFormFactory $createCharacterFormFactory): void {
    $this->createCharacterFormFactory = $createCharacterFormFactory;
  }
  
  /**
   * Get list of races of classes
   */
  protected function startup(): void {
    parent::startup();
    $this->classes = $this->model->getClassesList();
    $this->races = $this->model->getRacesList();
  }
  
  public function renderCreate(): void {
    $racesIds = $classesIds = [];
    foreach($this->races as $race) {
      $racesIds[] = $race->id;
    }
    foreach($this->classes as $class) {
      $classesIds[] = $class->id;
    }
    $this->template->races = $racesIds;
    $this->template->classes = $classesIds;
  }
  
  /**
   * Create form for creating character
   */
  protected function createComponentCreateCharacterForm(): Form {
    $form = $this->createCharacterFormFactory->create($this->races, $this->classes);
    $form->onSuccess[] = function(Form $form, array $values): void {
      $data = $this->userManager->create($values);
      if($data === null) {
        $this->forward("Character:exists");
      }
      $this->reloadIdentity();
      $this->forward("Character:created", ["data" => serialize($data)]);
    };
    return $form;
  }
  
  /**
   * @param string $data Serialized array with data
   */
  public function renderCreated(string $data): void {
    $data = unserialize($data);
    foreach($data as $key => $value) {
      $this->template->$key = $value;
    }
  }
}
?>