<?php
declare(strict_types=1);

namespace HeroesofAbenez\Chat\Commands;

use HeroesofAbenez\Model\Guild;
use HeroesofAbenez\Model\NotInGuildException;
use HeroesofAbenez\Model\MissingPermissionsException;
use HeroesofAbenez\Model\PlayerNotFoundException;
use HeroesofAbenez\Model\PlayerNotInGuildException;
use HeroesofAbenez\Model\CannotPromoteHigherRanksException;
use HeroesofAbenez\Model\CannotPromoteToGrandmasterException;
use HeroesofAbenez\Model\CannotHaveMoreDeputiesException;
use Nette\Localization\Translator;

/**
 * Chat Command Promote
 *
 * @author Jakub Konečný
 */
final class PromoteCommand extends \HeroesofAbenez\Chat\BaseChatCommand
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
            $this->model->promote($id);
            $message = $this->translator->translate("messages.guild.promoted");
        } catch (NotInGuildException) {
            $message = $this->translator->translate("errors.guild.notInGuild");
        } catch (MissingPermissionsException) {
            $message = $this->translator->translate("errors.guild.missingPermissions");
        } catch (PlayerNotFoundException) {
            $message = $this->translator->translate("errors.guild.playerDoesNotExist");
        } catch (PlayerNotInGuildException) {
            $message = $this->translator->translate("errors.guild.playerNotInGuild");
        } catch (CannotPromoteHigherRanksException) {
            $message = $this->translator->translate("errors.guild.cannotPromoteHigherRanks");
        } catch (CannotPromoteToGrandmasterException) {
            $message = $this->translator->translate("errors.guild.cannotPromoteToGrandmaster");
        } catch (CannotHaveMoreDeputiesException) {
            $message = $this->translator->translate("errors.guild.cannotHaveMoreDeputies");
        }
        return $message;
    }
}
