<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype;

use Crell\Serde\Serde;
use Symfony\Component\Yaml\Yaml;

class SerdeYamlFilePass
{
    public function __construct(private readonly string $filename, private readonly Serde $serde) {}

    public function __invoke(SettingsSchema $schema): void
    {
        //$array = Yaml::parseFile($this->filename);

        /** @var SettingsSchema $defs */
        $defs = $this->serde->deserialize(file_get_contents($this->filename), from: 'yaml', to: SettingsSchema::class);

        $schema->mergeSchema($defs);

//        // @todo There is no error handling at all here. There needs to be.
//        $defs = array_map(SettingDefinition::__set_state(...), $array);
//
//        foreach ($defs as $key => $def) {
//            $schema->setDefinition($key, $def);
//        }
    }

}
