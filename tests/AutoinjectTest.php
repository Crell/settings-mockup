<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype;

use Crell\SettingsPrototype\Attributes\Setting;
use Crell\SettingsPrototype\FakeServices\SettingAwareService;
use Crell\SettingsPrototype\SchemaType\IntType;
use Crell\SettingsPrototype\SchemaType\StringType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Compiler\AttributeAutoconfigurationPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class AutoinjectTest extends TestCase
{
    private ContainerBuilder $container;

    public function setUp(): void
    {
        parent::setUp();

        $this->container = $this->setupContainer();
    }

    protected function setupContainer(): ContainerBuilder
    {
        $container = new ContainerBuilder();
        $container->addCompilerPass(new AttributeAutoconfigurationPass());

        $this->setupContainerAttribute($container);
        $this->setupContainerSettingsSchema($container);
        $this->setupContainerSettings($container);

        return $container;
    }

    protected function setupContainerSettings(ContainerBuilder $container): void
    {
        $mockData = [
            1 => ['settings' => ['foo.bar.baz' => 5], 'parent' => 0],
            2 => ['settings' => [], 'parent' => 1],
            3 => ['settings' => [], 'parent' => 2],
        ];

        $def = new Definition(Settings::class, [
            new Reference(SettingsSchema::class),
            3,
            $mockData,
        ]);
        $def
            ->setAutowired(true)
            ->setAutoconfigured(true)
            ->setPublic(true);

        $container->setDefinition(Settings::class, $def);
    }

    protected function setupContainerSettingsSchema(ContainerBuilder $container): void
    {
        $def = new Definition(SettingsSchema::class);
        $def->addMethodCall('newDefinition', ['foo.bar.baz', new IntType(), 1]);
        $def->addMethodCall('newDefinition', ['beep.boop', new StringType(), 'not set']);
        $def
            ->setAutowired(true)
            ->setAutoconfigured(true)
            ->setPublic(true);

        $container->setDefinition(SettingsSchema::class, $def);
    }

    protected function setupContainerAttribute(ContainerBuilder $container): void
    {
        // ReflectionParameter is not technically a child of Reflector, due to PHP being buggy. It is, though, and this code works.
        // @phpstan-ignore-next-line
        $container->registerAttributeForAutoconfiguration(Setting::class, $this->autoconfigureSettingsAttribute(...));
    }

    protected function autoconfigureSettingsAttribute(ChildDefinition $definition, Setting $attribute, \ReflectionParameter $reflector): void
    {
        $settingDef = new Definition();
        $settingDef->setFactory([new Reference(Settings::class), 'get']);
        $settingDef->addArgument($attribute->name);

        $definition->addArgument($settingDef);
    }

    /**
     * @test
     */
    public function attributed_constructor_arg_is_injected_from_settings(): void
    {
        $def = new Definition(SettingAwareService::class);
        $def
            ->setAutowired(true)
            ->setAutoconfigured(true)
            ->setPublic(true);

        $this->container->setDefinition(SettingAwareService::class, $def);

        $this->container->compile();

        /** @var SettingAwareService $service */
        $service = $this->container->get(SettingAwareService::class);

        self::assertEquals(5, $service->getInjectedFoo());
    }
}
