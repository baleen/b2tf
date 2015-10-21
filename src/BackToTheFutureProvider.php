<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace Baleen\B2tf;

use Baleen\Cli\BaseCommand;
use Baleen\Cli\Provider\Services;
use Baleen\Migrations\Event\EventInterface;
use Baleen\Migrations\Event\Timeline\CollectionEvent;
use Baleen\Migrations\Timeline;
use Baleen\Migrations\Version;
use League\Container\ServiceProvider;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class BackToTheFutureProvider
 *
 * @author Gabriel Somoza <gabriel@strategery.io>
 */
class BackToTheFutureProvider extends ServiceProvider
{
    /** @var array  */
    protected $provides = [Services::APPLICATION_DISPATCHER];

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     */
    public function register()
    {
        $container = $this->getContainer();
        $dispatcher = new EventDispatcher();
        $dispatcher->addListener(ConsoleEvents::COMMAND, function (ConsoleCommandEvent $event) {
            $command = $event->getCommand();
            if ($command instanceof BaseCommand && $command->getName() == 'timeline:migrate') {
                $timeline = $command->getContainer()->get(Services::TIMELINE);
                $comparator = $command->getContainer()->get(Services::COMPARATOR);
                $this->registerDomainEvent($timeline, $comparator, $event->getOutput());
            }
        });
        $container->singleton(Services::APPLICATION_DISPATCHER, $dispatcher);
    }

    /**
     * registerDomainEvent
     *
     * @param Timeline $timeline
     * @param callable $comparator
     * @param OutputInterface $output
     */
    protected function registerDomainEvent(Timeline $timeline, callable $comparator, OutputInterface $output)
    {
        $domainDispatcher = $timeline->getEventDispatcher();
        $domainDispatcher->addListener(
            EventInterface::COLLECTION_BEFORE,
            function (CollectionEvent $event) use ($comparator, $output) {
                $first = $event->getCollection()->get('HEAD');
                if (!$first) {
                    $first = $event->getCollection()->first();
                }
                $last = $event->getCollection()->last();
                $b2tfVersion = new Version(20151021072800);
                if ( $comparator($first, $b2tfVersion) < 0
                     && $comparator($last, $b2tfVersion) >= 0
                ) { // we're crossing into the future!
                    $output->writeln([
                         '<info>+++ You\'re migrating past October 21st 2015 07:28 - #BackToTheFuture Day</info>',
                         '<info>+++</info>',
                         '<info>+++</info>    <comment>"If you put your mind to it, you can accomplish anything." - Marty McFly</comment>',
                         '<info>+++</info>',
                         '<info>+++ Baleen welcomes you to the future!!</info>',
                         '',
                    ]);
                }
            }
        );
    }
}
