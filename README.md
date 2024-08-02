<p align="center"><a href="https://github.com/beebmx/kirby-policy" target="_blank" rel="noopener"><img src="https://github.com/beebmx/kirby-policy/blob/main/assets/logo.svg?raw=true" width="125" alt="Kirby Policy Logo"></a></p>

<p align="center">
<a href="https://github.com/beebmx/kirby-policy/actions"><img src="https://img.shields.io/github/actions/workflow/status/beebmx/kirby-policy/tests.yml?branch=main" alt="Build Status"></a>
<a href="https://packagist.org/packages/beebmx/kirby-policy"><img src="https://img.shields.io/packagist/dt/beebmx/kirby-policy" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/beebmx/kirby-policy"><img src="https://img.shields.io/packagist/v/beebmx/kirby-policy" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/beebmx/kirby-policy"><img src="https://img.shields.io/packagist/l/beebmx/kirby-policy" alt="License"></a>
</p>

# Kirby Policy

An easy way to display different content in your Kirby panel for different user roles.

****

## Overview

- [1. Installation](#installation)
- [2. Usage](#usage)
- [3. Options](#options)
- [4. License](#license)
- [5. Credits](#credits)

## Installation

### Download

Download and copy this repository to `/site/plugins/kirby-policy`.

### Composer

```
composer require beebmx/kirby-policy
```

## Usage

You can create your YAML files as usual; you only need to follow a file structure and file content.

### File structure

In your `blueprints` directory, you can place your YAML files with the suffix `policy`:

```md
blueprints
  ├── pages
  │   ├── default.yml
  │   ├── home.policy.yml
  │   ├── simple.policy.yml
  │   ├── multiple.policy.yml
  │   ├── content.yml
  │   ├── blog.yml
  │   └── post.policy.yml
  ├── users
  │   ├── admin.yml
  │   └── editor.yml
  └── site.yml
```

> [!CAUTION]
> You cannot have a file without the prefix to avoid ignoring the policy file
> e.g. `home.yml` and `home.policy.yml` (just use `home.policy.yml`)

### File Content

The content of your YAML file need to add a `policy` property with the user `role` to every element than you need to customize:

```yaml
title: Page

tabs:

  # Only an admin will see the admin tab
  admin:
    label: Admin
    policy: admin

    columns:

      main:
        type: fields
        fields:

          item:
            label: Item
            type: text

  content:
    label: Content
    icon: page

    columns:

      main:
        width: 2/3
        sections:
          content:
            type: fields
            fields:
              text:
                label: Text
                type: text

              # Only an admin will see the admin field
              admin:
                label: Admin
                type: text
                policy:
                  - admin

              # Only an editor will see the editor field
              editor:
                label: Editor
                type: text
                policy:
                  - editor

              # An admin and editor will see the mixed field
              mixed:
                label: Mixed
                type: text
                policy:
                  - admin
                  - editor

      sidebar:
        sticky: true
        width: 1/3
        sections:
          pages:
            type: pages
            template: default
          files:
            type: files

```

> [!NOTE]
> The `policy` property can be a string or an array of `roles`

## Options

| Option                       | Default |      Types       | Description                             |
|:-----------------------------|:-------:|:----------------:|:----------------------------------------|
| beebmx.kirby-policy.excluded |  users  | `array` `string` | Excluded blueprints                     |
| beebmx.kirby-policy.suffix   | policy  |     `string`     | Blueprint suffix (e.g. file.policy.yml) |


## License

Licensed under the [MIT](LICENSE.md).

## Credits

- Fernando Gutierrez [@beebmx](https://github.com/beebmx)
- [jonatanjonas](https://github.com/jonatanjonas) `logo`
- [All Contributors](../../contributors)
