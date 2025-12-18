<?php
declare(strict_types=1);

namespace HeroesofAbenez\Model;

use HeroesofAbenez\Orm\CharacterClass;
use HeroesofAbenez\Orm\CharacterRace;
use HeroesofAbenez\Orm\CharacterSpecialization;

/**
 * Character Builder
 *
 * @author Jakub Konečný
 */
final class CharacterBuilder
{
    /** @var int */
    public const SPECIALIZATION_LEVEL = 15;

    private function calculateStat(string $stat, CharacterClass $class, CharacterRace $race, int $level): float
    {
        /** @var int $value */
        $value = $race->$stat + $class->$stat;
        $value += $class->{$stat . "Grow"} * ($level - 1);
        if ($level > 1 && $stat === $class->mainStat) {
            $value += ($level - 1) * $class->statPointsLevel;
        }
        return $value;
    }

    private function calculateSpecialistStat(string $stat, CharacterSpecialization $specialization, int $level): float
    {
        $value = ($specialization->{$stat . "Grow"} * ($level));
        if ($stat === $specialization->mainStat) {
            $value += ($level * $specialization->statPointsLevel);
        }
        return $value;
    }

    private function checkSpecialization(int $level, CharacterClass $class, CharacterSpecialization $specialization = null): void
    {
        if ($level < self::SPECIALIZATION_LEVEL) {
            if ($specialization !== null) {
                throw new CannotChooseSpecializationException();
            }
            return;
        } elseif ($specialization === null) {
            throw new SpecializationNotChosenException();
        } elseif ($specialization->class->id !== $class->id) {
            throw new SpecializationNotAvailableException();
        }
    }

    /**
     * @return array{strength: float, dexterity: float, constitution: float, intelligence: float, charisma: float}
     */
    public function create(CharacterClass $class, CharacterRace $race, int $level = 1, CharacterSpecialization $specialization = null): array
    {
        $this->checkSpecialization($level, $class, $specialization);
        $specializationLevel = 0;
        if ($specialization !== null) {
            $specializationLevel = $level - self::SPECIALIZATION_LEVEL + 1;
            $level = self::SPECIALIZATION_LEVEL - 1;
        }
        $data = [];
        $data["strength"] = $this->calculateStat("strength", $class, $race, $level);
        $data["dexterity"] = $this->calculateStat("dexterity", $class, $race, $level);
        $data["constitution"] = $this->calculateStat("constitution", $class, $race, $level);
        $data["intelligence"] = $this->calculateStat("intelligence", $class, $race, $level);
        $data["charisma"] = $this->calculateStat("charisma", $class, $race, $level);
        if ($specialization !== null) {
            $data["strength"] += $this->calculateSpecialistStat("strength", $specialization, $specializationLevel);
            $data["dexterity"] += $this->calculateSpecialistStat("dexterity", $specialization, $specializationLevel);
            $data["constitution"] += $this->calculateSpecialistStat("constitution", $specialization, $specializationLevel);
            $data["intelligence"] += $this->calculateSpecialistStat("intelligence", $specialization, $specializationLevel);
            $data["charisma"] += $this->calculateSpecialistStat("charisma", $specialization, $specializationLevel);
        }
        array_walk($data, function (&$value): void {
            $value = (int) $value;
        });
        return $data;
    }
}
