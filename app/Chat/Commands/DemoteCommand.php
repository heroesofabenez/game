<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat\Commands;

use HeroesofAbenez\Model\Guild;
use HeroesofAbenez\Model\NotInGuildException;
use HeroesofAbenez\Model\MissingPermissionsException;
use HeroesofAbenez\Model\PlayerNotFoundException;
use HeroesofAbenez\Model\PlayerNotInGuildException;
use HeroesofAbenez\Model\CannotDemoteHigherRanksException;
use HeroesofAbenez\Model\CannotDemoteLowestRankException;
use HeroesofAbenez\Model\CannotPromoteToGrandmasterException;
use Nette\Localization\Translator;

/**
 * Chat Command Demote
 *
 * @author Jakub Konečný
 */
final class DemoteCommand extends \HeroesofAbenez\Chat\ChatCommand
{
    public function __construct(private readonly Guild $model, private readonly Translator $translator)
    {
    }

    public function execute(int $id = null): string
    {
        if ($id === null) {
            return "";
        }
        try {
            $this->model->demote($id);
            $message = $this->translator->translate("messages.guild.demoted");
        } catch (NotInGuildException) {
            $message = $this->translator->translate("errors.guild.notInGuild");
        } catch (MissingPermissionsException) {
            $message = $this->translator->translate("errors.guild.missingPermissions");
        } catch (PlayerNotFoundException) {
            $message = $this->translator->translate("errors.guild.playerDoesNotExist");
        } catch (PlayerNotInGuildException) {
            $message = $this->translator->translate("errors.guild.playerNotInGuild");
        } catch (CannotDemoteHigherRanksException) {
            $message = $this->translator->translate("errors.guild.cannotPromoteHigherRanks");
        } catch (CannotPromoteToGrandmasterException) {
            $message = $this->translator->translate("errors.guild.cannotDemoteHigherRanks");
        } catch (CannotDemoteLowestRankException) {
            $message = $this->translator->translate("errors.guild.cannotDemoteLowestRank");
        }
        return $message;
    }
}
