<?php
declare(strict_types=1);

require __DIR__ . "/../vendor/autoload.php";

$configurator = new \Nette\Configurator();
$configurator->enableTracy(__DIR__ . "/../log");
$configurator->setTempDirectory(__DIR__ . "/../temp");
$configurator->addConfig(__DIR__ . "/../app/config/main.neon");
$configurator->addConfig(__DIR__ . "/../tests/local.neon");
$configurator->addParameters([
  "appDir" => __DIR__ . "/../app",
]);
$container = $configurator->createContainer();
$orm = $container->getByType(\HeroesofAbenez\Orm\Model::class);

$errorFound = false;

/** @var \Nextras\Orm\Collection\ICollection|\HeroesofAbenez\Orm\CharacterRace[] $races */
$races = $orm->races->findAll();
if($races->count() === 0) {
  echo "No race found\n";
  $errorFound = true;
}
if($races->findBy(["playable" => true])->countStored() === 0) {
  echo "No playable race found\n";
  $errorFound = true;
}

/** @var \Nextras\Orm\Collection\ICollection|\HeroesofAbenez\Orm\CharacterClass[] $races */
$classes = $orm->classes->findAll();
$classTotalGrowth = 2.05;
$petLevels = [8, 15, 30, 45, 60];
if($classes->count() === 0) {
  echo "No class found\n";
  $errorFound = true;
}
if($classes->findBy(["playable" => true])->countStored() === 0) {
  echo "No playable class found\n";
  $errorFound = true;
}
foreach($classes as $class) {
  $totalGrowth = $class->strengthGrow + $class->dexterityGrow + $class->constitutionGrow + $class->intelligenceGrow + $class->charismaGrow + $class->statPointsLevel;
  if($totalGrowth !== $classTotalGrowth) {
    echo "Total growth for class $class->name (#$class->id) is not $classTotalGrowth but $totalGrowth\n";
    $errorFound = true;
  }
  if($class->specializations->countStored() === 0) {
    echo "Class $class->name (#$class->id) has no specialization\n";
    $errorFound = true;
  }
  if($class->attackSkills->get()->findBy(["neededLevel" => 1])->countStored() === 0) {
    echo "Class $class->name (#$class->id) has no attack skill for level 1\n";
    $errorFound = true;
  }
  if($class->specialSkills->get()->findBy(["neededLevel" => 1])->countStored() === 0) {
    echo "Class $class->name (#$class->id) has no special skill for level 1\n";
    $errorFound = true;
  }
  if($class->specialSkills->get()->findBy(["neededLevel" => 10])->countStored() === 0) {
    echo "Class $class->name (#$class->id) has no special skill for level 10\n";
    $errorFound = true;
  }
  if($class->playable) {
    foreach($petLevels as $level) {
      if($class->petTypes->get()->findBy(["requiredLevel" => $level])->countStored() === 0) {
        echo "Class $class->name (#$class->id) has no pet for level $level\n";
        $errorFound = true;
      }
    }
  }
}

/** @var \Nextras\Orm\Collection\ICollection|\HeroesofAbenez\Orm\CharacterSpecialization[] $races */
$specializations = $orm->specializations->findAll();
$specializationTotalGrowth = 2.5;
if($specializations->count() === 0) {
  echo "No specialization found\n";
  $errorFound = true;
}
foreach($specializations as $specialization) {
  $totalGrowth = $specialization->strengthGrow + $specialization->dexterityGrow + $specialization->constitutionGrow + $specialization->intelligenceGrow + $specialization->charismaGrow + $specialization->statPointsLevel;
  if($totalGrowth !== $specializationTotalGrowth) {
    echo "Total growth for specialization $specialization->name (#$specialization->id) is not $specializationTotalGrowth but $totalGrowth\n";
    $errorFound = true;
  }
  if($specialization->attackSkills->get()->findBy(["neededLevel" => 15])->countStored() === 0) {
    echo "Specialization $specialization->name (#$specialization->id) has no attack skill for level 15\n";
    $errorFound = true;
  }
  if($specialization->specialSkills->get()->findBy(["neededLevel" => 15])->countStored() === 0) {
    echo "Specialization $specialization->name (#$specialization->id) has no special skill for level 15\n";
    $errorFound = true;
  }
}

if($errorFound) {
  exit(1);
} else {
  echo "Everything ok";
  exit(0);
}
?>