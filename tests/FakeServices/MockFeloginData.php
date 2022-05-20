<?php

declare(strict_types=1);

namespace Crell\SettingsPrototype\FakeServices;

use Crell\SettingsPrototype\SchemaType\BoolType;
use Crell\SettingsPrototype\SchemaType\EmailType;
use Crell\SettingsPrototype\SchemaType\IntType;
use Crell\SettingsPrototype\SchemaType\StringType;
use Crell\SettingsPrototype\SettingsSchema;

class MockFeloginData
{
    public function __invoke(SettingsSchema $schema): void
    {
        $def = $schema->newDefinition('styles.content.loginform.pid', new StringType(), '0');
        $def->form->label = 'User Storage Page';
        $def->form->description = 'Define the Storage Folder with the Website User Records, using a comma separated list or single value';

        $def = $schema->newDefinition('styles.content.loginform.recursive', new IntType(allowedValues: [0, 1, 2, 3, 4, 255]), 0);
        $def->form->label = 'Recursive';
        $def->form->description = 'If set, also subfolder at configured recursive levels of the User Storage Page will be used';

        $def = $schema->newDefinition('styles.content.loginform.showForgotPasswordLink', new BoolType(), false);
        $def->form->label = 'Display Password Recovery Link';
        $def->form->description = 'If set, the section in the template to display the link to the forget password dialogue is visible.';

        $def = $schema->newDefinition('styles.content.loginform.showPermaLogin', new BoolType(), false);
        $def->form->label = 'Display Remember Login Option';
        $def->form->description = 'If set, the section in the template to display the option to remember the login (with a cookie) is visible.';

        $def = $schema->newDefinition('styles.content.loginform.showLogoutFormAfterLogin', new BoolType(), false);
        $def->form->label = 'Disable redirect after successful login, but display logout-form';
        $def->form->description = 'If set, the logout form will be displayed immediately after successful login.';

        $def = $schema->newDefinition('styles.content.loginform.emailFrom', new EmailType(), '');
        $def->form->label = 'Email Sender Address';
        $def->form->description = 'Email address used as sender of the change password emails';

        $def = $schema->newDefinition('styles.content.loginform.dateFormat', new StringType(), 'Y-m-d H:i');
        $def->form->label = 'Date format';
        $def->form->description = 'Format for the link is valid until message (forget password email)';

        // ...

    }
}
