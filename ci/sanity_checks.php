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

$errors = [];

/** @var \Nextras\Orm\Collection\ICollection|\HeroesofAbenez\Orm\CharacterRace[] $races */
$races = $orm->races->findAll();
if($races->count() === 0) {
  $errors[] = "No race found";
}
if($races->findBy(["playable" => true])->countStored() === 0) {
  $errors[] = "No playable race found";
}

/** @var \Nextras\Orm\Collection\ICollection|\HeroesofAbenez\Orm\CharacterClass[] $races */
$classes = $orm->classes->findAll();
$classTotalGrowth = 2.05;
$petLevels = [8, 15, 30, 45, 60];
if($classes->count() === 0) {
  $errors[] = "No class found";
}
if($classes->findBy(["playable" => true])->countStored() === 0) {
  $errors[] = "No playable class found";
}
foreach($classes as $class) {
  $totalGrowth = $class->strengthGrow + $class->dexterityGrow + $class->constitutionGrow + $class->intelligenceGrow + $class->charismaGrow + $class->statPointsLevel;
  if($totalGrowth !== $classTotalGrowth) {
    $errors[] = "Total growth for class $class->name (#$class->id) is not $classTotalGrowth but $totalGrowth";
  }
  if($class->specializations->countStored() === 0) {
    $errors[] = "Class $class->name (#$class->id) has no specialization";
  }
  if($class->attackSkills->toCollection()->findBy(["neededLevel" => 1])->countStored() === 0) {
    $errors[] = "Class $class->name (#$class->id) has no attack skill for level 1";
  }
  if($class->specialSkills->toCollection()->findBy(["neededLevel" => 1])->countStored() === 0) {
    $errors[] = "Class $class->name (#$class->id) has no special skill for level 1";
  }
  if($class->specialSkills->toCollection()->findBy(["neededLevel" => 10])->countStored() === 0) {
    $errors[] = "Class $class->name (#$class->id) has no special skill for level 10";
  }
  if($class->playable) {
    foreach($petLevels as $level) {
      if($class->petTypes->toCollection()->findBy(["requiredLevel" => $level])->countStored() === 0) {
        $errors[] = "Class $class->name (#$class->id) has no pet for level $level";
      }
    }
  }
}

/** @var \Nextras\Orm\Collection\ICollection|\HeroesofAbenez\Orm\CharacterSpecialization[] $races */
$specializations = $orm->specializations->findAll();
$specializationTotalGrowth = 2.5;
if($specializations->count() === 0) {
  $errors[] = "No specialization found";
}
foreach($specializations as $specialization) {
  $totalGrowth = $specialization->strengthGrow + $specialization->dexterityGrow + $specialization->constitutionGrow + $specialization->intelligenceGrow + $specialization->charismaGrow + $specialization->statPointsLevel;
  if($totalGrowth !== $specializationTotalGrowth) {
    $errors[] = "Total growth for specialization $specialization->name (#$specialization->id) is not $specializationTotalGrowth but $totalGrowth";
  }
  if($specialization->attackSkills->toCollection()->findBy(["neededLevel" => 15])->countStored() === 0) {
    $errors[] = "Specialization $specialization->name (#$specialization->id) has no attack skill for level 15";
  }
  if($specialization->specialSkills->toCollection()->findBy(["neededLevel" => 15])->countStored() === 0) {
    $errors[] = "Specialization $specialization->name (#$specialization->id) has no special skill for level 15";
  }
}

if(count($errors) > 0) {
  foreach($errors as $error) {
    echo $error . "\n";
  }
  exit(1);
} else {
  echo "Everything ok";
  exit(0);
}
?>