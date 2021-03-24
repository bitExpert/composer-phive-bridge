<?php

declare(strict_types=1);

/**
 * Copyright bitExpert AG
 *
 * Licenses under the MIT-license. For details see the included file LICENSE.md
 */

namespace bitExpert\ComposerPhiveBridge;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;
use function getcwd;

class Plugin implements PluginInterface, EventSubscriberInterface
{
	/**
	 * Apply plugin modifications to Composer
	 *
	 * @param Composer $composer
	 * @param IOInterface $io
	 */
	public function activate(Composer $composer, IOInterface $io)
	{
	}

	/**
	 * Remove any hooks from Composer
	 *
	 * This will be called when a plugin is deactivated before being
	 * uninstalled, but also before it gets upgraded to a new version
	 * so the old one can be deactivated and the new one activated.
	 *
	 * @param Composer $composer
	 * @param IOInterface $io
	 */
	public function deactivate(Composer $composer, IOInterface $io)
	{
		// TODO: Implement deactivate() method.
	}

	/**
	 * Prepare the plugin to be uninstalled
	 *
	 * This will be called after deactivate.
	 *
	 * @param Composer $composer
	 * @param IOInterface $io
	 */
	public function uninstall(Composer $composer, IOInterface $io)
	{
		// TODO: Implement uninstall() method.
	}

	/**
	 * Returns an array of event names this subscriber wants to listen to.
	 *
	 * The array keys are event names and the value can be:
	 *
	 * * The method name to call (priority defaults to 0)
	 * * An array composed of the method name to call and the priority
	 * * An array of arrays composed of the method names to call and respective
	 *   priorities, or 0 if unset
	 *
	 * For instance:
	 *
	 * * array('eventName' => 'methodName')
	 * * array('eventName' => array('methodName', $priority))
	 * * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
	 *
	 * @return array The event names to listen to
	 */
	public static function getSubscribedEvents()
	{
		return [
			ScriptEvents::PRE_AUTOLOAD_DUMP => [
				['preAutoloadDump', 0],
			],
		];
	}

	public function preAutoloadDump(Event $event): void
	{
		$this->getPhiveHandler($event)
			->assertPhiveAvailability()
			->install();
	}

	private function getPhiveHandler(Event $event): PhiveHandler
	{
		return new PhiveHandler(getcwd(), $event->getIO());
	}
}
