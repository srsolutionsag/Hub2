## Installation

### Install Hub2 plugin
Start at your ILIAS root directory 

```
mkdir -p Customizing/global/plugins/Services/Cron/CronHook
cd Customizing/global/plugins/Services/Cron/CronHook
git clone https://github.com/studer-raimann/Hub2.git
```

### Install dependencies via composer
```
cd Hub2
composer install
```

If you run composer from vagrant box, remember to run it as user `www-data`.
```
sudo -u www-data composer install
```
