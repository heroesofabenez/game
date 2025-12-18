<?php
declare(strict_types=1);

namespace HeroesofAbenez\Presenters;

use Tester\Assert;
use Nette\Application\Responses\ForwardResponse;

/**
 * TPresenter
 *
 * @author Jakub Konečný
 */
trait TPresenter
{
    use \Testbench\TPresenter;

    protected function checkForward(string $destination, string $to = "", array $params = [], array $post = []): ForwardResponse
    {
        /** @var ForwardResponse $response */
        $response = $this->check($destination, $params, $post);
        if ($this->testbench_exception === null) {
            Assert::same(200, $this->getReturnCode());
            Assert::type(ForwardResponse::class, $response);
            if ($to !== "") {
                $target = $response->getRequest()->presenterName . ":" . $response->getRequest()->parameters["action"];
                if ($to !== $target) {
                    Assert::fail("does not forward to $to, but {$target}");
                }
            }
        }
        return $response;
    }
}
