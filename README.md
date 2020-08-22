# botify-template

> **Warn! Work In Progress**

## About
Ready-for-use skeleton project for Botify library + web dashboard (stats, analytics, manage)

## Install
```bash
$ git clone https://github.com/aethletic/botify-template.git botify
```

```bash
$ cd botify
```

## Structure
- **bot** - Directory where all bot logic is stored.
  - **events** - Event Processing Files.
  - **libs** - Third-party libraries, helper classes, etc.
  - **localization** - Localization files.
  - **modules** - Modules directory.
  - **registers** - Keyboard files, replicas, emojis.
  - **bootstrap.php** - A file that initializes everything necessary for work. Modules, localization, etc. are also connected here.
- **config** - Directory for storing any configuration files.
  - bot.php - File with bot settings.
- **storage** - Directory for storing any files.
- **bot.php** - The entry point of the bot for webhook.
