Laravel Localization Tool
=========================

Simple tool to help with localization process on laravel template files.

##Input
Laravel Blade template with @lang definitions before correct strings. Ex: x:@lang('project.exit_button')Exit

```
<p class="status">
    @lang('projects.project_status')Status
</p>
```

##Output
Laravel localization array and blade template with cut off ogiriginal strings.

```
<?php
return array(
    'project_status' => 'Status',
);
```

```
<p class="status">
    @lang('projects.project_status')
</p>
```

##Roadmap
* add warnings about strings without @lang definition

