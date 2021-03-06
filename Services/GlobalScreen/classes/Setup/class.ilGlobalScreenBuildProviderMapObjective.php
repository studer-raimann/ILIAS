<?php

use ILIAS\GlobalScreen\Scope\Layout\Provider\ModificationProvider;
use ILIAS\GlobalScreen\Scope\MainMenu\Provider\StaticMainMenuProvider;
use ILIAS\GlobalScreen\Scope\MetaBar\Provider\StaticMetaBarProvider;
use ILIAS\GlobalScreen\Scope\Notification\Provider\NotificationProvider;
use ILIAS\GlobalScreen\Scope\Tool\Provider\DynamicToolProvider;
use ILIAS\Setup;

/**
 * Class ilGSBootLoaderBuilder
 *
 * @package ILIAS\GlobalScreen\BootLoader
 */
class ilGlobalScreenBuildProviderMapObjective extends Setup\Artifact\BuildArtifactObjective
{
    public function getArtifactPath() : string
    {
        return "Services/GlobalScreen/artifacts/global_screen_providers.php";
    }


    public function build() : Setup\Artifact
    {
        $class_names = [];
        $i = [
            StaticMainMenuProvider::class,
            StaticMetaBarProvider::class,
            DynamicToolProvider::class,
            ModificationProvider::class,
            NotificationProvider::class,
        ];

        $finder = new Setup\ImplementationOfInterfaceFinder();
        foreach ($i as $interface) {
            $class_names[$interface] = iterator_to_array(
                $finder->getMatchingClassNames($interface)
            );
        }

        return new Setup\Artifact\ArrayArtifact($class_names);
    }
}
